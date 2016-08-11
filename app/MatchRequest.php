<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MatchRequest extends Model
{
  protected $table = 'match_requests';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'author_id', 'target_id', 'accepted',
  ];
}
