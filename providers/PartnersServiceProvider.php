<?php

namespace Pensoft\Searchextension\providers;

use OFFLINE\SiteSearch\Classes\Providers\ResultsProvider;

class PartnersServiceProvider extends ResultsProvider
{
    public function search()
    {
        $controller = \Cms\Classes\Controller::getController() ?? new \Cms\Classes\Controller();
        // Get your matching models
        if(class_exists(\Pensoft\Partners\Models\Partners::class)){
            $matching = \Pensoft\Partners\Models\Partners::where('instituion', 'ilike', "%{$this->query}%")
                ->orWhere('content', 'ilike', "%{$this->query}%")->get();

            // Create a new Result for every match
            foreach ($matching as $match) {
                $result            = $this->newResult();
                $result->relevance = 1;
                $result->title     = '';
                $result->text      = $match->content;
                $result->url       = $controller->pageUrl('partners', ['code' => $match->id]);
                // $result->thumb     = $match->cover;
                $result->model     = $match;
                $result->meta      = [];

                // Add the results to the results collection
                $this->addResult($result);
            }
        }

        return $this;
    }
    public function displayName()
    {
        return 'Partners';
    }

    public function identifier()
    {
        return 'Pensoft.Partners';
    }
}
