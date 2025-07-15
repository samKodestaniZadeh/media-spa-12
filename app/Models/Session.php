<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Session extends Model
{
    use HasFactory;
    protected $table = 'sessions';
    protected $fillable = [
        'sessionable_type',
        'sessionable_id',
    ];
    private static function getId()
    {
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    /**

     * Returns all the guest users.

     *

     * @param \Illuminate\Database\Eloquent\Builder $query

     * @return \Illuminate\Database\Eloquent\Builder

     */

    public function scopeGuests(Builder $query)

    {

        return $query->whereNull('user_id');

    }

    /**

     * Returns all the registered users.

     *

     * @param \Illuminate\Database\Eloquent\Builder $query

     * @return \Illuminate\Database\Eloquent\Builder

     */

    public function scopeRegistered(Builder $query)

    {
        return $query->whereNotNull('user_id')->with('users');

    }

    /**
 * Updates the session of the current user.
 *

     * @param \Illuminate\Database\Eloquent\Builder $query
 * @return int
 */

    public function scopeUpdateCurrent(Builder $query)

    {
        $user = Auth::check();

        if($user)

            $userid = Auth::user()->id;

        return $query->where('id', Session::getId())->update([

            'user_id' => $user ? $userid : null,
        ]);

    }
    /**

     * Returns all the users within the given activity.

     *

     * @param \Illuminate\Database\Eloquent\Builder $query

     * @param int $limit

     * @return \Illuminate\Database\Eloquent\Builder

     */

    public function scopesubSecond($query)

    {

        $lastActivity = strtotime(Carbon::now()->subSecond());

        return $query->where('last_activity', '>=', $lastActivity);

    }
    /**

     * Returns all the users within the given activity.

     *

     * @param \Illuminate\Database\Eloquent\Builder $query

     * @param int $limit

     * @return \Illuminate\Database\Eloquent\Builder

     */

    public function scopesubMinute($query)

    {

        $lastActivity = strtotime(Carbon::now()->subMinute());

        return $query->where('last_activity', '>=', $lastActivity);

    }
    /**

     * Returns all the users within the given activity.

     *

     * @param \Illuminate\Database\Eloquent\Builder $query

     * @param int $limit

     * @return \Illuminate\Database\Eloquent\Builder

     */

    public function scopesubHour($query)

    {

        $lastActivity = strtotime(Carbon::now()->subHour());

        return $query->where('last_activity', '>=', $lastActivity);

    }
    /**

     * Returns all the users within the given activity.

     *

     * @param \Illuminate\Database\Eloquent\Builder $query

     * @param int $limit

     * @return \Illuminate\Database\Eloquent\Builder

     */
    public function scopesubDay($query)

    {

        $lastActivity = strtotime(Carbon::now()->subDay());

        return $query->where('last_activity', '>=', $lastActivity);

    }
    /**

     * Returns all the users within the given activity.

     *

     * @param \Illuminate\Database\Eloquent\Builder $query

     * @param int $limit

     * @return \Illuminate\Database\Eloquent\Builder

     */
    public function scopesubWeek($query)

    {

        $lastActivity = strtotime(Carbon::now()->subWeek());

        return $query->where('last_activity', '>=', $lastActivity);

    }
    /**

     * Returns all the users within the given activity.

     *

     * @param \Illuminate\Database\Eloquent\Builder $query

     * @param int $limit

     * @return \Illuminate\Database\Eloquent\Builder

     */

    public function scopesubWeekday($query)

    {

        $lastActivity = strtotime(Carbon::now()->subWeekday());

        return $query->where('last_activity', '>=', $lastActivity);

    }
    /**

     * Returns all the users within the given activity.

     *

     * @param \Illuminate\Database\Eloquent\Builder $query

     * @param int $limit

     * @return \Illuminate\Database\Eloquent\Builder

     */

    public function scopesubMonth($query)

    {

        $lastActivity = strtotime(Carbon::now()->subMonth());

        return $query->where('last_activity', '>=', $lastActivity);

    }
    /**

     * Returns all the users within the given activity.

     *

     * @param \Illuminate\Database\Eloquent\Builder $query

     * @param int $limit

     * @return \Illuminate\Database\Eloquent\Builder

     */

    public function scopesubYear($query)

    {

        $lastActivity = strtotime(Carbon::now()->subYear());

        return $query->where('last_activity', '>=', $lastActivity);

    }
}
