<?php 

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository 
{
  public function querySingle(array $where, bool $getSingleRecord = false) : ?User
  {
    return $this->query($where, True);
  }

  private function query(array $where, bool $getSingleRecord = false): User|Collection|null
  {
    $result = User::where($where);
    if($getSingleRecord) {
      return $result->first();
    }
    return $result->get();
  }
}