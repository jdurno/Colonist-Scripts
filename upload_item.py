import internetarchive
import os
import time

#the directory with all the files we're going to upload
uploadDir = '/home/jdurno/Projects/Colonist/IA-upload/items2upload/theNightlyDump'

#monkey-patch boto to allow mixed-case bucketnames
import boto
def check_lowercase_bucketname(n):
	return True
boto.s3.connection.check_lowercase_bucketname = check_lowercase_bucketname

#keep track of what we've uploaded in a log file
fh = open("uploaded_files.txt", "a")



#Production keys for the internet archive account
os.environ['AWS_ACCESS_KEY_ID']='xxxxxxxxxxxxxxxxxxxx'
os.environ['AWS_SECRET_ACCESS_KEY']='xxxxxxxxxxxxxxxxxx'


#get all files in upload dir, 
#determine the date from the filename
#construct appropriate metadata and bucketname, upload

filesInUploadDir = sorted(os.listdir(uploadDir))
for i in filesInUploadDir:
    if i.endswith(".pdf"): 
        print i
        fileparts = i.split('.')
        basename = fileparts[0]
        identifier = 'dailycolonist' + basename + 'uvic'
        year = basename[:4]
        month = basename[4:-2]
        day = basename[6:]
        print identifier
        title = 'Daily Colonist (' + year + '-' + month + '-' + day + ')'
        
        metadata = dict(language='eng', 
        		contributor='University of Victoria Libraries',
        		collection=['dailycolonist','universityofvictorialibraries'],
        		sponsor='University of Victoria Libraries',
        		title=title,
        		date=year,
        		month=month,
        		day=day,
        		mediatype='texts',
        		uploader='iarchive@uvic.ca',
        		subject='British Columbia; Newspapers',
        		descripton='Historic newspaper from British Columbia, Canada'
        		)
        
        #print(metadata)
        
        file2upload = uploadDir + '/' + i
        print file2upload
        item = internetarchive.Item(identifier)
        item.upload_file(file2upload, metadata=metadata, remote_name=i)
        time.sleep(5)
        fh.write(identifier + "\t" + title + "\n")
        
	continue
    else:
        continue

fh.close()
print "all done\n"






#modify metadata
#item = internetarchive.Item('BritishColonistTestUploadIssue4')
#md = dict(subject='British Columbia - Newspapers', title='British Colonist (1858-12-18)')
#item.modify_metadata(md)
