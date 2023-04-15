<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        "name",
        "url",
        "status"
    ];

    public function userHasAccess(User $user): bool
    {
        return in_array($user->id, $this->users);
    }

    /**
     * Get the supporters for the site.
     */
    public function supporters()
    {
        return $this->hasMany(Supporter::class);
    }

    public $casts = [
        "users" => "array"
    ];

    /**
     * Find a site by id, name or url.
     */
    public static function findInAnyOrFail($query)
    {
        $site = self::where('id', $query)
            ->orWhere('name', $query)
            ->orWhere('url', "LIKE", "%{$query}%")
            ->first();
        if (!$site) {
            abort(404);
        } else {
            return $site;
        }
    }

    /**
     * Get all supporter custom fields for the site.
     */
    public function supporterCustomFields()
    {
        $supporters = $this->supporters;
        $fields = [];
        foreach ($supporters as $supporter) {
            array_push($fields, array_keys($supporter->data));
        }
        $fields = array_unique(array_merge(...$fields));
        return $fields;
    }
}
