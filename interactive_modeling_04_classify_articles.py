#!/usr/bin/python -tt
# -*- coding: utf-8 -*-
# Author, Ali Hashmi, MIT
import codecs
import sys
import re
import os
import time
import stopwords
import grammarmodule
import unicodedata
import pprint, pickle
import json

from sklearn.datasets import fetch_20newsgroups
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.feature_extraction.text import HashingVectorizer
from sklearn.feature_selection import SelectKBest, chi2
from sklearn.linear_model import RidgeClassifier
from sklearn.svm import LinearSVC
from sklearn.linear_model import SGDClassifier
from sklearn.linear_model import Perceptron
from sklearn.linear_model import PassiveAggressiveClassifier
from sklearn.naive_bayes import BernoulliNB, MultinomialNB
from sklearn.neighbors import KNeighborsClassifier
from sklearn.neighbors import NearestCentroid
from sklearn.utils.extmath import density
from sklearn import metrics

CORPUS_CHUNK_FILE = "corpus_chunk_file.JSON"

#############################################################
# read from the modified output file.
#############################################################
fj = open('topicsfileout.JSON','r')
strJSONtopicfile = fj.read()
fj.close()
jsonTopicModelData = json.loads(strJSONtopicfile)

#############################################################
# GRAB THE ARTICLES FILE OR CORPUS FILE
# 1,2
#############################################################
fj = open(CORPUS_CHUNK_FILE,'r')
strJSONArticles = fj.read()
fj.close()
articlesData = json.loads(strJSONArticles)


#############################################################
# PUT KEYWORD/FEATURES FROM ARTICLES FROM THE CORPUS FILE INTO AN ARRAY
#############################################################
corpusArrayFeaturesTxt=[]
try:
    for i in articlesData['articles']:
        corpusArrayFeaturesTxt.append(i['keywords'])
except UnicodeEncodeError:
    print "***UnicodeEncodeError***"

#############################################################
# GRAB CORPUS DATA
#############################################################

topicModelArray=[]
topicModelArray_target=[]
try:
    for i in jsonTopicModelData['topics']:
        topicModelArray_target.append(i['topic'])
        topicModelArray.append(i['keywords'])
except UnicodeEncodeError:
    print "***UnicodeEncodeError ***"

#############################################################
#use TFIDF vectorizier
#############################################################
vectorizer = TfidfVectorizer(sublinear_tf=True, max_df=0.5,
                                 stop_words='english', lowercase=1)
corpusArrayFeaturesTxt_vec = vectorizer.fit_transform(corpusArrayFeaturesTxt+topicModelArray)
#print "corpusArrayFeaturesTxt_vec=%s" % str(corpusArrayFeaturesTxt_vec.shape)

topicModelArray_vec=vectorizer.transform(topicModelArray)
#print "topicModelArray_Vec=%s" % str(topicModelArray_vec.shape)

classifier = (Perceptron(n_iter=50))
classifier.fit(topicModelArray_vec, topicModelArray_target)

y00_20 =classifier.predict(corpusArrayFeaturesTxt_vec[0:20])
y40_60 =classifier.predict(corpusArrayFeaturesTxt_vec[40:60])
y80_100 =classifier.predict(corpusArrayFeaturesTxt_vec[80:100])
y120_140 =classifier.predict(corpusArrayFeaturesTxt_vec[120:140])
y160_180 =classifier.predict(corpusArrayFeaturesTxt_vec[160:180])

#### READ CORPUS DATA
### 1, 2
### THIS IS FOR 100 ARTICLES
fj = open(CORPUS_CHUNK_FILE,'r')
articlesdata = json.load(fj)
fj.close()
for xitem in xrange(len(articlesdata["articles"])):
     if (xitem >=0 and xitem < 20):
           articlesdata["articles"][xitem]["predict"]=y00_20[xitem]
           articlesdata["articles"][xitem]["predict_copy"]=y00_20[xitem]
     elif (xitem >=40 and xitem < 60):
           articlesdata["articles"][xitem]["predict"]=y40_60[xitem-40]
           articlesdata["articles"][xitem]["predict_copy"]=y40_60[xitem-40]
     elif (xitem >=80 and xitem < 100):
           articlesdata["articles"][xitem]["predict"]=y80_100[xitem-80]
           articlesdata["articles"][xitem]["predict_copy"]=y80_100[xitem-80]
     elif (xitem >=120 and xitem < 140):
           articlesdata["articles"][xitem]["predict"]=y120_140[xitem-120]
           articlesdata["articles"][xitem]["predict_copy"]=y120_140[xitem-120]
     elif (xitem >=160 and xitem < 180):
           articlesdata["articles"][xitem]["predict"]=y160_180[xitem-180]
           articlesdata["articles"][xitem]["predict_copy"]=y160_180[xitem-180]
        
##### WRITE TO FILE:::CORPUS_CHUNK_FILE
####
###
#writing back to the file
jsonFile = open(CORPUS_CHUNK_FILE, "w+")
jsonFile.write(json.dumps(articlesdata))
jsonFile.close()

