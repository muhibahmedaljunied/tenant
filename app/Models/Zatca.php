<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zatca extends Model
{
    use HasFactory, BelongsToBusiness;
    protected $guarded = [];
    const STATUS_PHASE2 = 'phase_2';
    const STATUS_PHASE1 = 'phase_1';
    public function responses()
    {
        return $this->morphMany(ZatcaResponse::class, 'responser');
    }
    public function isProductionCertified()
    {
        return filled($this->production_certificate) && filled($this->production_certificate) && filled($this->production_csid);
    }
    public function isPreProductionCertified()
    {
        return filled($this->certificate) && filled($this->secret) && filled($this->private_key);
    }
    public function isZatcaConfigured()
    {
        return $this->isPreProductionCertified() || $this->isProductionCertified();
    }
}
