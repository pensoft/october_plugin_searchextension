<?php

namespace Pensoft\Searchextension\providers;

use OFFLINE\SiteSearch\Classes\Providers\ResultsProvider;

class UsersServiceProvider extends ResultsProvider
{
    public function search()
    {
        $controller = \Cms\Classes\Controller::getController() ?? new \Cms\Classes\Controller();
        // Get your matching models
        if(class_exists(\Rainlab\User\Models\User::class)){
            $matching = \Rainlab\User\Models\User::where('name', 'ilike', "%{$this->query}%")
                ->orWhere('surname', 'ilike', "%{$this->query}%")
                ->orWhere('email', 'ilike', "%{$this->query}%")
                ->orWhereRaw('( name || \' \'|| surname ) ilike ?', "%{$this->query}%")
                ->get();

            // Create a new Result for every match
            foreach ($matching as $match) {
                $result            = $this->newResult();

                $result->relevance = 1;
                $result->title     = $match->name.' '.$match->surname;
                $result->text      = $match->email;
                $result->url       = $controller->pageUrl('users').'#'.$match->email;
                // $result->thumb     = $match->cover_image;
                $result->model     = $match;
                $result->meta      = [
                ];

                // Add the results to the results collection
                $this->addResult($result);
            }
        }

        return $this;
    }
    public function displayName()
    {
        return 'Users';
    }

    public function identifier()
    {
        return 'Rainlab.Users';
    }
}
