# scraper.php

This is the main class for the scraper. It's the one that kicks off all jobs and processes in the flow of scraping.

## General Workflow.

1. Get all options from the Interface.
2. Foreach scrape, run the processing.
   1. Scrape the API, returning a class with a property `items` in it.
   2. Filter the result items.
   3. Map the filtered items to post / image and meta objects.
   4. Import those objects into the correct post type / taxonomy and link them all together.
   5. Create a schedule in the cron for the job to run on a timer.
3. Run the housekeep scripts to clear up any disabled crons
4. Run the housekeep script to delete any matched posts.

## Objects

Each part of the workflow essentially does 4 jobs.
1. Creates a new object for that job (scrape, filter, mapper, etc...)
2. Sets all the data and options that class needs
3. Runs it.
4. Pulls back the results into the scraper class for th enext part to use.

## Main data

    $this->options->scrape[$this->_scrape_key]

This is where all the data for each scrape is held in the scraper object. Each part of the flow will add to this with it's results.
The different components are held in these parts of the object:

    yt_scrape_enabled = is this on/off to scrape                           
    yt_scrape_id      = unique id.                                
    yt_scrape_auth    = authentication details.                             
    yt_scrape_filter  = filter details                            
    yt_scrape_mapper  = mapper details                            
    yt_scrape_import  = import details                            
    yt_scrape_response = response back from the API.              
    yt_scrape_filtered = response back after being filtered.      
    yt_scrape_mapped   = response after being mapped (post array) 
    yt_scrape_imported = response from import job.                