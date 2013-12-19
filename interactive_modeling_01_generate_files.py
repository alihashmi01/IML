# -*- coding: utf-8 -*-
"""
Created on Mon Dec  9 23:21:10 2013
Ali Hashmi, MIT
@author: hash
"""

#!/usr/bin/python -tt
# -*- coding: utf-8 -*-
import codecs
import sys
import re
import os
import time
import unicodedata
import pprint, pickle
import json

# dependency: documents_mix100_file
#INPUT:
#        INPUTFILE = documents_mix100_file.pkl
#OUTPUT:
#CORPUSFILE1 = corpus_chunk_file_01.JSON
#CORPUSFILE2 = corpus_chunk_file_02.JSON
#LADFILE1 = lda_input_file_01.txt
#LDAFILE2 = lda_input_file_02.txt
#this file has the following chunks of topics:
'''
"Stocks and Bonds"                                    
"Politics and Government"                             
"Medicine and Health"                                 
"Baseball"                                            
"Computers and the Internet"                          
"Music"                                               
"Books and Literature"                                
"Weather"                                             
"Basketball"                                          
"Motion Pictures"                                     
"Hockey, Ice"                                         
"United States International Relations"               
"Football"                                            
'''

#cleaned xml to grammar based file without stopwords
INPUTFILE = 'documents_mix100_file.pkl'

CORPUSFILE1 = 'corpus_chunk_file_01.JSON'
CORPUSFILE2 = 'corpus_chunk_file_02.JSON'

LDAFILE1 = 'lda_input_file_01.txt'
LDAFILE2 = 'lda_input_file_02.txt'
MAXCOUNT = 40

TOPICS_ARRAY1 =  ["Weather","Football", "Motion Pictures", "United States International Relations","Music" ]
TOPICS_ARRAY2 =  ["Stocks and Bonds","Politics and Government", "Medicine and Health", "Baseball","Computers and the Internet"]

mix100 = []
documents_file = open(INPUTFILE, 'rb')
mix100 = pickle.load(documents_file)
documents_file.close()


#remove special characters excluding full stop and space
for x in xrange(len(mix100)):
     mix100[x][1] = re.sub('[^A-Za-z0-9 \.]+', '',  mix100[x][1])
     mix100[x][1] = re.sub(' +', ' ',               mix100[x][1])
     mix100[x][2] = re.sub('[^A-Za-z0-9 \.]+', '',  mix100[x][2])
     mix100[x][3] = re.sub('[^A-Za-z0-9 \.]+', '',  mix100[x][3])
     mix100[x][4] = re.sub('[^A-Za-z0-9 \.]+', '',  mix100[x][4])

def generate_files(array_of_topics, corpus_file, lda_file):
     ######################################################################
     # Topic Set 1
     # ["Weather","Football", "Motion Pictures", "United States International Relations","Music" ]:
     # ["Stocks and Bonds","Politics and Government", "Medicine and Health", "United States International Relations","Computers and the Internet"]
     ######################################################################
     #this is storing it in JSON
     fj = open(corpus_file,'w')
     #this is for lda file
     ft = open(lda_file,'w')
     fj.write("{\"articles\": [")
     
     TOTALCOUNT = 0 
     for item in array_of_topics:
         ctr = 0
         for x in xrange(len(mix100)):
             if mix100[x][2]==item and ctr < MAXCOUNT:
                 fj.write("{ \"keywords\": \"%s\"," % mix100[x][0])
                 fj.write("  \"article\": \"%s\"," % mix100[x][1])
                 fj.write("  \"topic\": \"%s\"," % mix100[x][2])
                 fj.write("  \"headline\": \"%s\"," % mix100[x][3])
                 fj.write("  \"predict\": \"notclassified\",")
                 fj.write("  \"predict_copy\": \"notclassified\",")
                 fj.write("  \"predict_one\": \"notclassified\",")
                 fj.write("  \"predict_one_copy\": \"notclassified\",")
                 fj.write("  \"predict_two\": \"notclassified\",")
                 fj.write("  \"predict_two_copy\": \"notclassified\",")
                 fj.write("  \"predict_three\": \"notclassified\",")
                 fj.write("  \"predict_three_copy\": \"notclassified\",")
                 fj.write("  \"predict_user1\": \"not_classified\",")
                 fj.write("  \"predict_user2\": \"not_classified\",")
                 fj.write("  \"predict_user3\": \"not_classified\",")
                 fj.write("  \"predict_user4\": \"not_classified\",")
                 fj.write("  \"filename\": \"%s\" " % mix100[x][4])
                 #print to file
                 #lda is on MAX -20 only
                 if ctr < (MAXCOUNT-20):
                     ft.write(mix100[x][0]+'\n')
                 if TOTALCOUNT == MAXCOUNT*5 - 1:
                     fj.write("}")
                 else:
                     fj.write("},")
                 ctr = ctr + 1
                 TOTALCOUNT= TOTALCOUNT+1
     fj.write("]}")
     fj.close()
     ft.close()

#call the function
generate_files(TOPICS_ARRAY1,CORPUSFILE1, LDAFILE1)
generate_files(TOPICS_ARRAY2,CORPUSFILE2, LDAFILE2)



