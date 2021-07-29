<?php

namespace Pensoft\Searchextension\providers;

use OFFLINE\SiteSearch\Classes\Providers\ResultsProvider;

class JumbotronServiceProvider extends ResultsProvider
{
    public function search()
    {
        $controller = \Cms\Classes\Controller::getController() ?? new \Cms\Classes\Controller();
        // Get your matching models
        if (class_exists(\Pensoft\Jumbotron\Models\Jumbotron::class)) {
            $matching = \Pensoft\Jumbotron\Models\Jumbotron::where('title', 'ilike', "%{$this->query}%")
                ->orWhere('body', 'ilike', "%{$this->query}%")
                ->get();

            // Create a new Result for every match
            foreach ($matching as $match) {
                $result            = $this->newResult();

                $result->relevance = 1;
                $result->title     = $match->title;
                $result->text      = $match->body;
                $result->url       = $controller->pageUrl('about');
                // $result->thumb     = $match->cover;
                $result->model     = $match;
                // $result->meta      = [
                //     'some_data' => $match->some_other_property,
                // ];

                // Add the results to the results collection
                $this->addResult($result);
            }
        }

        return $this;
    }
    public function displayName()
    {
        return 'Page';
    }

    public function identifier()
    {
        return 'Pensoft.Jumbotron';
    }
}
