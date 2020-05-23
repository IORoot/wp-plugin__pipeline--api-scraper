# API Directory

The API directory is where all of the instances of scraping happen. There is a directory for each instance type. The name of the directory will be used to list the request type select box, so name it correctly.

## API

The `api.php` file is the class for creating an instance of a scrape. The rest of the scraper expects the API to return a specfic format to be able to be filtered and mapped.

## Response

The response of the API needs to be formatted in this way:

    yt_scrape_response: stdClass
        > items: array(x)
            > 0: stdClass
                [ ITEM DATA HERE ]
            > 1: stdClass
                [ ITEM DATA HERE ]
            > 2: stdClass
                [ ITEM DATA HERE ]

If the response is not in this format, the filter, mapper and importer will not work.

## Files

`api.php` does any substitution and token replacements on the search string, then creates an instance of the correct API and runs it.

`api_list.php` returns a list of api directories.

`request_list.php` returns a 'nice name' list of all requests from all APIs.

`/API/requests/` directory holds all of the request types.

`/API/response/` deals with error checking of the responses.

`/tokens` holds an instance of a token replacement for the search strings.