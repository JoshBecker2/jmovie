# cleans errors when scraping data and adds IDs to all of the movies 
# for the database

SOURCE_FILE = "movies.txt"
DEST_FILE = "movies.csv"

data = ""
with open(SOURCE_FILE, "r") as f:
    data = str(f.read())
    f.close()

entries = data.split("\n")

newEntries = []

for i in range(len(entries)):
    newEntries.append(str(entries[i]) + "," + str(i))

with open(DEST_FILE, "w") as f:
    for entry in newEntries:
        if len(entry) > 100: # a proper entry is always longer than 100 characters
            f.write(entry + "\n")
    f.close()