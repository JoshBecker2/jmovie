<h1>jmovie</h1>
<h2>The Movie Search Engine</h2>

<br>

<h2>About</h2>
<p>This project was developed to watch movies on my favorite sites like myflixer and soap2day without the ads that lead you to viruses, ergo compromising the device.<br>
It is built with a Selenium Python library that simulates a user watching a movie, i.e. going to the movie itself; clicking through the ads; etc., and then grabbing data about each title along the way.
The metadata for each movie is stored in a CSV format which is then uploaded to a MySQL database. This project also comes with a front-end that displays each title, and comes with a search feature as well.
</p>

<h2>Replicating</h2>
<p>The way that I was able to derive this content was through experimenting with the inspect tool in Chrome and exploiting how the videos were loaded on the front-end. Should you want to try this on your own, much of the logic will need to be changed as this code workes explicitly on 123moviesfree.net. Each website is different, and the site that I settled on was not my first choice-it was the first one that worked after lots of trial and error. After the scraping process is done, cleanup.py will need to be run. This file generates another file that cleans up any errors from the scraping process and adds IDs to each title. This was intended for running multiple MovieScraper classes in different terminals to make the whole process go quicker.</p>

<h2>Displaying Data on the Front-End</h2>
<p>The data, as previously stated, is generated in a CSV file where the values are as follows:
    <ul>
        <li>title - Title of the movie</li>
        <li>search - A regex of just the alphanumeric characters of title (used for the search feature)</li>
        <li>url - The URL of the movie</li>
        <li>poster - The URL of the movie's poster</li>
        <li>views - The number of views each movie has</li>
        <li>id - Added in with cleanup.py, it identifies each movie in the CSV file</li>
    </ul>

    This project makes use of PHP for communicating with the SQL server.

</p>

<h2>Legality</h2>
<p>I do not own the sites that actually host the movie files themselves. My code simply gathers content that is already out on the internet, regardless. I make no claims of ownership of the movies themselves, nor the physical servers which host them.</p>