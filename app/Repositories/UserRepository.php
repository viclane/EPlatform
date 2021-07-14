<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{

    private $wantInvalidateUser = false;

    /**
     * Constructor
     *
     * @param $model - User
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function wantInvalidate()
    {
        $this->wantInvalidateUser = true;
    }

    public function findUser(
        $q = null,
        $type = null,
        $sort = 'first_name',
        $order = 'desc',
        $nb = null,
        $current = null
    ) {
        $query = $this->newQuery();

        if ($type) {
            $query = $query->where('type', $type);
        }

        if ($q) {
            $query = $query->whereRaw("(first_name LIKE '%{$q}%' OR last_name LIKE '%{$q}%')");
//            $query = $query->whereRaw("(first_name LIKE '%{$q}%' OR last_name LIKE '%{$q}%' OR login LIKE '%{$q}%')");
        }

        $sort = $sort ?? 'first_name';

        if (!$order || !in_array($order, ['asc', 'desc'])) {
            $order = 'desc';
        }

        $query = $query->orderBy($sort, $order);

        if ($nb && $current) {
            $query = $query->paginate($nb, ['*'], 'page', $current);
        } else if ($nb) {
            $query = $query->paginate($nb);
        }

        return $query;
    }

    public function getinstructors()
    {
        return $this->newQuery()->where('type', 'instructor')->get();
    }

    public function getstudents()
    {
        return $this->newQuery()->where('type', 'student')->get();
    }

    public function all()
    {
        return $this->newQuery()->get();
    }

    public function newQuery()
    {
        $newQuery = $this->model->newQuery()->where('id', '!=', 1);

        if ($this->wantInvalidateUser) {
            $newQuery = $newQuery->where('type', null);
        }

        return $newQuery;
    }
}
