<?php

namespace Modules\Tracker\Entities;

use App\User;
use App\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class Track extends Model
{
    use HasFactory;


    protected $logAttributes = ['name', 'description', 'sector_id', 'distribution_area_id', 'province_id', 'user_id', 'contacts.name'];
    protected $table = 'dm_tracks';

    protected $fillable = ['name', 'description', 'sector_id', 'distribution_area_id', 'province_id', 'user_id', 'business_id'];


    public function getActivityLogOptions(){

        return Log::info(['name', 'description', 'sector_id', 'distribution_area_id', 'province_id', 'user_id']);

        // return LogOptions::defaults()
        //     ->logOnly(['name', 'description', 'sector_id', 'distribution_area_id', 'province_id', 'user_id'])
        //     ->logOnlyDirty();
    }

    public function distributionArea(){
        return $this->belongsTo(DistributionArea::class);
    }

    public function sector(){
        return $this->belongsTo(Sector::class);
    }

    public function province(){
        return $this->belongsTo(Province::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function contacts(){
        return $this->hasMany(Contact::class, 'track_id');
    }

    public function syncContacts($ids){
        $trackContacts = $this->contacts()->pluck('id')->toArray();
        $detached = array_diff($trackContacts, $ids);
        $attached = array_diff($ids, $trackContacts);

        foreach($attached as $id){
            $contact = Contact::find($id);
            $this->contacts()->save($contact);
            activity()
                ->performedOn($this)
                ->causedBy(auth()->user())
                ->withProperties(['id' => $contact->id, 'name' => $contact->name])
                ->log('contact.attached');
        }
        foreach($detached as $id){
            $contact = Contact::find($id);
            $contact->track_id = null;
            $contact->save();
            activity()
                ->performedOn($this)
                ->causedBy(auth()->user())
                ->withProperties(['id' => $contact->id, 'name' => $contact->name])
                ->log('contact.detached');
        }



        $this->user->selected_contacts = true;
        $this->user->save();
        $this->user->contactAccess()->sync($ids);
    }
}
