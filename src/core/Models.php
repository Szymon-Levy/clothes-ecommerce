<?php

namespace Core;

use Models\Newsletter;
use Models\Contact;

class Models
{
    protected ?Newsletter $newsletter = null;
    protected ?Contact $contact = null;
    
    public function __construct(
        protected DataBase $database,
        protected Container $container
    ){}

    public function newsletter()
    {
        if ($this->newsletter === null) {
            $this->newsletter = $this->container->get(Newsletter::class);
        }
        return $this->newsletter;
    }

    public function contact()
    {
        if ($this->contact === null) {
            $this->contact = $this->container->get(Contact::class);
        }
        return $this->contact;
    }
}