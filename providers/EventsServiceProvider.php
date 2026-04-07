<?php

namespace Pensoft\Searchextension\providers;

use OFFLINE\SiteSearch\Classes\Providers\ResultsProvider;

class EventsServiceProvider extends ResultsProvider
{
    public function search(): self
    {
        $controller = \Cms\Classes\Controller::getController() ?? new \Cms\Classes\Controller();
        // Get your matching models
        if(class_exists(\Pensoft\Calendar\Models\Entry::class)){
            $matching = \Pensoft\Calendar\Models\Entry::where('title', 'ilike', "%{$this->query}%")
                ->orWhere('description', 'ilike', "%{$this->query}%")
                ->orWhere('place', 'ilike', "%{$this->query}%")
                ->get();
    
            // Create a new Result for every match
            foreach ($matching as $match) {
                $result            = $this->newResult();
    
                $result->relevance = 1;
                $result->title     = $match->title;
                $result->text      = $match->description;
                $result->url       = $controller->pageUrl('events', ['slug' => $match->slug]);
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
    public function displayName(): string
    {
        return 'Events';
    }

    public function identifier(): string
    {
        return 'Pensoft.Events';
    }
}
