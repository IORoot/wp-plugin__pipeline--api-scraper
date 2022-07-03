

<div id="top"></div>

<div align="center">


<img src="https://svg-rewriter.sachinraja.workers.dev/?url=https%3A%2F%2Fcdn.jsdelivr.net%2Fnpm%2F%40mdi%2Fsvg%406.7.96%2Fsvg%2Fyoutube.svg&fill=%23DC2626&width=200px&height=200px" style="width:200px;"/>

<h3 align="center">Wordpress API Scraper for YouTube</h3>

<p align="center">
    Predominantly built for interacting with the youtube API. This plugin is what runs parkourpulse.com
</p>    
</div>

##  1. <a name='TableofContents'></a>Table of Contents


* 1. [Table of Contents](#TableofContents)
* 2. [About The Project](#AboutTheProject)
	* 2.1. [Built With](#BuiltWith)
	* 2.2. [Installation](#Installation)
* 3. [Usage](#Usage)
	* 3.1. [Scrape](#Scrape)
	* 3.2. [Authenticate](#Authenticate)
	* 3.3. [Search](#Search)
	* 3.4. [Filter](#Filter)
	* 3.5. [Mapping](#Mapping)
	* 3.6. [import](#import)
	* 3.7. [Housekeep](#Housekeep)
	* 3.8. [Schedule](#Schedule)
* 4. [Customising](#Customising)
* 5. [Testing](#Testing)
* 6. [Contributing](#Contributing)
* 7. [License](#License)
* 8. [Contact](#Contact)



##  2. <a name='AboutTheProject'></a>About The Project

The API Scraper was a project born out of wanting to build parkourpulse.com. Using wordpress, I wanted to pull various query data out of youtube and present it in a nice way.

This plugin grabs the data, filters it, maps it to post objects, imports them, housekeeps any old data and puts it all on a schedule.

![scraper](https://github.com/IORoot/wp-plugin__api-scraper/blob/master/files/images/SCRAPER.png?raw=true)


<p align="right">(<a href="#top">back to top</a>)</p>



###  2.1. <a name='BuiltWith'></a>Built With

This project was built with the following frameworks, technologies and software.

* [Youtube API](https://developers.google.com/youtube/v3/docs)
* [ACF Pro](https://advancedcustomfields.com/)
* [PHPUnit](https://phpunit.de/)
* [Composer](https://getcomposer.org/)
* [PHP](https://php.net/)
* [Wordpress](https://wordpress.org/)

<p align="right">(<a href="#top">back to top</a>)</p>



###  2.2. <a name='Installation'></a>Installation

> This was built with ACF PRO - Please make sure it is installed before installing this plugin.

These are the steps to get up and running with this plugin.

1. Clone the repo into your wordpress plugin folder
    ```bash
    git clone https://github.com/IORoot/wp-plugin__api-scraper ./wp-content/plugins/api-scraper
    ```
1. Activate the plugin.


<p align="right">(<a href="#top">back to top</a>)</p>

##  3. <a name='Usage'></a>Usage

The API Scraper Wordpress plugin will allow you to get data from various API endpoints and output the results as posts into your custom post types / taxonomies. 

This plugin is quite complicated and requires a lot of configuration. It was built to be a general Scraper rather than a specialised one that focuses on a single API.

It has many very powerful features that can be quite dangerous if not used correctly. However, this is also it's strength.

For more detailed usage, please see the [Wiki](https://github.com/IORoot/wp-plugin__api-scraper/wiki).


Below is a brief description of each tab in the system:

###  3.1. <a name='Scrape'></a>Scrape

The main controller. The 'scrape' tab allows you to select all of the other components that make up the scrape you're going to run.

![scrape](https://github.com/IORoot/wp-plugin__api-scraper/blob/master/files/images/Scrape.png?raw=true)

###  3.2. <a name='Authenticate'></a>Authenticate

Any authentication methods for youtube APIs or other API's can be setup here.

This is handy for creating multiple youtube accounts and using them for different API Scrapes.


![auth](https://github.com/IORoot/wp-plugin__api-scraper/blob/master/files/images/Authenticate.png?raw=true)

###  3.3. <a name='Search'></a>Search

The search is the main component of *how* you are going to use the YouTube API. You can specify what your search query will be.

![search](https://github.com/IORoot/wp-plugin__api-scraper/blob/master/files/images/Search.png?raw=true)

###  3.4. <a name='Filter'></a>Filter

The filter is run once your search query has returned results. It will perform any dynamic filters required on the results.

![filter](https://github.com/IORoot/wp-plugin__api-scraper/blob/master/files/images/Filter.png?raw=true)

###  3.5. <a name='Mapping'></a>Mapping

Once the data has been retrieved and filtered you need to specify how your wordpress posts will be populated with that data.

Title, Post content, Images, Meta fields, etc... Map sources data to destination fields.

![map](https://github.com/IORoot/wp-plugin__api-scraper/blob/master/files/images/Mapping.png?raw=true)

###  3.6. <a name='import'></a>import

The mappings have been defined, but now the scraper needs to import the generated post into a particular post-type, taxonomy, category, etc...

![import](https://github.com/IORoot/wp-plugin__api-scraper/blob/master/files/images/Import.png?raw=true)

###  3.7. <a name='Housekeep'></a>Housekeep

Everything is running smoothly and you're generating content. However, you're not removing any old posts or data you don't want anymore. The housekeeping tab allows you to manage this.

![housekeep](https://github.com/IORoot/wp-plugin__api-scraper/blob/master/files/images/Housekeep.png?raw=true)

###  3.8. <a name='Schedule'></a>Schedule 

Once everything is ready to go you can put it on a scheduled timer. Now you'll be getting your up-to-date scrape data without you having to manually run it.

![schedule](https://github.com/IORoot/wp-plugin__api-scraper/blob/master/files/images/Schedule.png?raw=true)


##  4. <a name='Customising'></a>Customising

Please refer to the [Wiki](https://github.com/IORoot/wp-plugin__api-scraper/wiki)

##  5. <a name='Testing'></a>Testing

This plugin has PHPUnit tests built in. These are all located in the `/tests` folder. The config file is in the root of the plugin `phpunit.xml.dist`.

You also have a demo dashboard in the `/files/` folder that can be used to show example data.


<p align="right">(<a href="#top">back to top</a>)</p>


##  6. <a name='Contributing'></a>Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue.
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<p align="right">(<a href="#top">back to top</a>)</p>



##  7. <a name='License'></a>License

Distributed under the MIT License.

MIT License

Copyright (c) 2022 Andy Pearson

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

<p align="right">(<a href="#top">back to top</a>)</p>



##  8. <a name='Contact'></a>Contact

Author Link: [https://github.com/IORoot](https://github.com/IORoot)

<p align="right">(<a href="#top">back to top</a>)</p>

