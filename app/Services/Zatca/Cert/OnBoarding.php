<?php
namespace App\Services\Zatca\Cert;
use Exception;
use App\Models\Zatca;
use App\Services\Zatca\Utils\ZatcaRequest;

class OnBoarding
{
    private $taxPayerConfig;

    public function __construct($taxPayerConfig)
    {
        $this->taxPayerConfig = $taxPayerConfig;
        $this->generateConfigFile();
    }

    public function generateConfigFile()
    {
        if ($this->taxPayerConfig->is_production) {
            return $this;
        }
        $certificateTemplateName = 'PREZATCA-Code-Signing';
        $config_file = "
        oid_section = OIDs
        [ OIDs ]
        certificateTemplateName= 1.3.6.1.4.1.311.20.2

        [ req ]
        default_bits 	= 2048
        emailAddress 	= {$this->taxPayerConfig->email_address}
        req_extensions	= v3_req
        x509_extensions 	= v3_ca
        prompt = no
        default_md = sha256
        req_extensions = req_ext
        distinguished_name = dn

        [ v3_req ]
        basicConstraints = CA:FALSE
        keyUsage = digitalSignature, nonRepudiation, keyEncipherment

        [req_ext]
        certificateTemplateName = ASN1:PRINTABLESTRING:{$certificateTemplateName}
        subjectAltName = dirName:alt_names

        [ v3_ca ]


        # Extensions for a typical CA


        # PKIX recommendation.

        subjectKeyIdentifier=hash

        authorityKeyIdentifier=keyid:always,issuer:always
        [ dn ]
        CN ={$this->taxPayerConfig->common_name}  				                    # Common Name
        C={$this->taxPayerConfig->country_name}							            # Country Code e.g SA
        OU={$this->taxPayerConfig->organization_unit_name}							# Organization Unit Name
        O={$this->taxPayerConfig->organization_name}							    # Organization Name

        [alt_names]
        SN={$this->taxPayerConfig->egs_serial_number}				                # EGS Serial Number 1-ABC|2-PQR|3-XYZ
        UID={$this->taxPayerConfig->trn}						                    # Organization Identifier (VAT Number)
        title={$this->taxPayerConfig->invoice_type}								    # Invoice Type
        registeredAddress={$this->taxPayerConfig->registered_address}  	 			# Address
        businessCategory={$this->taxPayerConfig->business_category}					# Business Category";
        $setting = Zatca::find($this->taxPayerConfig->id);
        $setting->update([$this->taxPayerConfig->cnf => base64_encode($config_file)]);
    }
    /**
     *
     * generate csr request file
     *
     */
    public function generatePemsKeys()
    {
        if ($this->taxPayerConfig->is_production) {
            return $this;
        }
        $setting = Zatca::find($this->taxPayerConfig->id);
        // convert config column to temp file start
        $temp = tmpfile();
        fwrite($temp, base64_decode($setting->cnf));
        fseek($temp, 0);
        $tmpfile_path = stream_get_meta_data($temp)['uri'];
        $file_cnf = file_get_contents($tmpfile_path);

        $config = [
            "config" => $tmpfile_path,
            'private_key_type' => OPENSSL_KEYTYPE_EC,
            'curve_name' => 'secp256k1'
        ];
        $res = openssl_pkey_new($config);
        if (! $res) {
            echo 'ERROR: Fail to generate private key. -> ' . openssl_error_string();
            exit;
        }

        // Generate Private Key and Store it start
        openssl_pkey_export($res, $priv_key, NULL, $config);
        $setting->update([$this->taxPayerConfig->private_key => base64_encode($priv_key)]);
        // Generate Private Key and Store it end

        // Get The Public Key and Store it start
        $key_detail = openssl_pkey_get_details($res);
        $pub_key = $key_detail["key"];
        $setting->update([$this->taxPayerConfig->public_key => base64_encode($pub_key)]);
        // Get The Public Key and Store it end

        $dn = [
            "commonName" => $this->taxPayerConfig->common_name,
            "organizationalUnitName" => $this->taxPayerConfig->organization_unit_name,
            "organizationName" => $this->taxPayerConfig->organization_name,
            "countryName" => $this->taxPayerConfig->country_name
        ];
        // Generate a certificate signing request start
        $csr = openssl_csr_new($dn, $priv_key, array('digest_alg' => 'sha256', "req_extensions" => "req_ext", 'curve_name' => 'secp256k1', "config" => $tmpfile_path));
        openssl_csr_export($csr, $csr_string);
        $setting->update([$this->taxPayerConfig->csr_request => base64_encode($csr_string)]);
        // Generate a certificate signing request end

        fclose($temp); // this removes the file

        // return same object
        return $this;
    }


    /**
     *
     * generate x509 certificate from Zatca API'S
     *
     */
    public function Cert509($type)
    {
        $setting = Zatca::find($this->taxPayerConfig->id);
        // set post fields
        if ($type == 'production') {
            $post = [
                'compliance_request_id' => $setting->csid,
            ];
        } elseif ($type == 'compliance') {
            $post = [
                'csr' => $setting->csr_request,
            ];
        }
        $url = ($type == 'production') ? '/production/csids' : '/compliance';
        try {
            $request = (new ZatcaRequest)->baseRequest('POST', $url, $post);
            $response = $request->getBody()->getContents();
            $setting->responses()->create([
                'response' => $response,
                'status' => 'PASS'
            ]);
            $response = json_decode($response);

            $this->handleSuccess(
                $response,
                $type,
                $setting,
            );
            return [
                'success' => true,
                'message' => $response->dispositionMessage
            ];
        } catch (Exception $e) {
            $response = $e->getResponse();
            $response_source = $response->getBody()->getContents();
            $setting->responses()->create(['response' => json_encode($response_source), 'status' => 'INVALID']);
            $response = json_decode($response_source);
            if (! empty($response->errors) && count($response->errors) > 0) {
                return ['success' => false, 'message' => $response->errors[0]];
            } elseif (isset($response->code) && $response->code == 'Invalid-OTP') {
                return ['success' => false, 'message' => $response->message];
            } elseif (isset($response->code) && $response->code == 'Missing-ComplianceSteps') {
                return ['success' => false, 'message' => $response->message];
            } else {
                return ['success' => false, 'message' => $response_source];
            }
        }

    }

    /**
     *
     * issue x509 certificate
     *
     */
    public function IssueCert509()
    {
        if ($this->taxPayerConfig->is_production) {
            return $this->Cert509('production');
        }
        return $this->Cert509('compliance');
    }
    private function handleSuccess($response, $type, $setting)
    {
        $certificate = ($type == 'compliance') ? $this->taxPayerConfig->certificate : $this->taxPayerConfig->production_certificate;
        $secret = ($type == 'compliance') ? $this->taxPayerConfig->secret : $this->taxPayerConfig->production_secret;
        $csid = ($type == 'compliance') ? $this->taxPayerConfig->csid : $this->taxPayerConfig->production_csid;
        $setting->update([$certificate => $response->binarySecurityToken]);
        $setting->update([$secret => $response->secret]);
        $setting->update([$csid => $response->requestID]);
    }

}
