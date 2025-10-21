<?php


class employee {
public function __construct(
    protected string $name,
    protected string $role,
){}


public function get_role_description(): string {
return "this is a general employee";    
};




}

class manager extends employee {
    public function __construct(
        string $name,
        protected int $team_size,
    ){
        parent::__construct($name, "manager");
    }
    public function get_role_description(): string {
        return "this is a manager who manages a team of " . $this->team_size . " people.";
    }


class developer extends employee {
    public function __construct(
        string $name,
    ){
        parent::__construct($name, "developer");
    }
    }
}


?>