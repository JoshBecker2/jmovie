try: 
    from selenium import webdriver
    from selenium.webdriver.chrome.options import Options as ChromeOptions
    from selenium.webdriver.common.by import By
    from time import sleep
    import re
except Exception as e:
    print("Error importing modules. {}".format(e))
    exit()

class MovieScraper:
    
    # start/stopIdx refers to which pages this class will scrape for splitting up the workload
    # writeTo is the file for the output
    # timeout is how long the program will sleep for loading pages
    # implicitWait determines how long Selenium will wait before throwing an exception
    def __init__(self, startIdx, stopIdx, writeTo, timeout, implicitWait=5):

        self.startIdx = startIdx
        self.stopIdx = stopIdx
        self.writeTo = writeTo
        self.timeout = timeout
        self.implicitWait = implicitWait

        options = webdriver.ChromeOptions()
        options.add_argument("--log-level=3") # remove logs and run the browser in headless mode
        options.add_argument("--headless")

        # start the browser and loop through all the movie IDs
        self.driver = webdriver.Chrome(options=options)
        self.driver.implicitly_wait(self.implicitWait)

        print("Scraping pages %s to %s..." % (startIdx, stopIdx))
        self.getMovies(self.getBaseURLs()) # begin scraping

    # a base URL is defined as the "player" page for each movie, retrieved from the movie listing page (https://ww2.123moviesfree.net/movies/)
    def getBaseURLs(self):
        baseURLs = []
        count = 0

        for i in range(self.startIdx, self.stopIdx):
            self.driver.get("https://ww2.123moviesfree.net/movies/%s" % i)

            print("Progress...\t" + str(round(count / (self.stopIdx - self.startIdx) * 100, 2)) + "%", end='\r')
            count += 1

            sleep(self.timeout)
        
            movies = self.driver.find_elements(By.CLASS_NAME, "col")
            
            # get movie page URL
            for movie in movies:
                base = movie.get_attribute("innerHTML")
        
                if "rounded poster" in base: # if the link is to a movie 
                    baseURLs.append(base[base.index("<a href=\"") + 9:base.index("\" class=")])


        print("Found %s movies total..." % len(baseURLs))

        return baseURLs
    
    def getMovies(self, baseURLs):
        print("Finding movie URLs...")
        failed = [] # stores the failed baseURLs to be retried
        count = 0

        for url in baseURLs: # click on #play-now button to load the video with code then steal the iFrame source
            try:
                self.driver.get(url)
                sleep(self.timeout)

                # press the play button so that the video loads the iFrame source 
                activationButton = self.driver.find_element(By.ID, "play-now")
                activationButton.click()

                # close the popup window
                if (len(self.driver.window_handles) > 1):
                    parent = self.driver.window_handles[0]
                    child = self.driver.window_handles[1]
                    self.driver.switch_to.window(child)
                    self.driver.close()
                    self.driver.switch_to.window(parent)

                sleep(self.timeout)
                # get the title of the movie
                title = self.driver.find_elements(By.TAG_NAME, "h1")[0].get_attribute("innerHTML")
                
                poster = self.driver.find_elements(By.TAG_NAME, "picture")[0].get_attribute("innerHTML")
                poster = poster[poster.index("<source type=\"image/webp\" data-srcset=\""):poster.index(" 1x,")]
                frame = self.driver.find_element(By.ID, "playit")
                video = frame.get_attribute("src")

                if video != "":
                    with open(self.writeTo, "a") as f:
                        # title, search, url, poster, views, id (added later bc of parallel programs)
                        s = re.sub(r'[^a-zA-Z0-9]', '', title) # remove alphanumeric characters and spaces for searching 
                        f.write(title.replace(",", "%2C") + "," + s + "," + video + "," + poster[39:] + ",0\n")

            except:
                failed.append(url)

            count += 1
            print("Progress...\t" + str(round(count / len(baseURLs) * 100, 2)) + "%", end="\r")

        print("Added %s out of %s movies into %s" % (len(baseURLs) - len(failed), len(baseURLs), self.writeTo))

        if len(failed) > 0: # keep going until all the movies are accounted for
            print("Retrying failed movies (%s)..." % len(failed))
            self.getMovies(failed)

startIdx = int(input("Start Page: "))
stopIdx = int(input("Stop Page: "))
timeout = float(input("Timeout (s): "))
writeTo = str(input("Result file: "))

MovieScraper(startIdx=startIdx, stopIdx=stopIdx, timeout=timeout, writeTo=writeTo)