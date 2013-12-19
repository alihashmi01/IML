#!/usr/bin/python -tt
# -*- coding: utf-8 -*-
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

######################
#### SECOND PART
######################
#R: UPLOAD THE LDA KEY CLASSIFIER
fj = open('topicsfileout.JSON','r')
strJSONtopicfile = fj.read()
fj.close()
jsonData = json.loads(strJSONtopicfile)

#############################################################
# GRAB THE ARTICLES FILE OR CORPUS FILE
# 1,2
#############################################################
fj = open(CORPUS_CHUNK_FILE,'r')
strJSONArticles = fj.read()
fj.close()
articlesData = json.loads(strJSONArticles)

###R: APPEND only CLASSIFIED DATA
##
corpusArrayFeaturesClassified=[]
labels_corpusArrayFeaturesClassified_target=[] 
try:
    for item in articlesData['articles']:
        if item['predict'] != "notclassified":
            corpusArrayFeaturesClassified.append(item['keywords'])
            labels_corpusArrayFeaturesClassified_target.append(item['predict'])
except UnicodeEncodeError:
    print "***UnicodeEncodeError***"


#R: APPEND FULL DATA
corpusArrayFeaturesFull=[]
try:
    for item in articlesData['articles']:
            corpusArrayFeaturesFull.append(item['keywords'])
except UnicodeEncodeError:
    print "***UnicodeEncodeError***"


topicModelArray=[]
try:
    for i in jsonData['topics']:
        #### target + 5
        labels_corpusArrayFeaturesClassified_target.append(i['topic'])
        topicModelArray.append(i['keywords'])
except UnicodeEncodeError:
    print "***UnicodeEncodeError -- ******"
    
#use TFIDF vectorizier
vectorizer = TfidfVectorizer(sublinear_tf=True, max_df=0.5,
                                 stop_words='english', lowercase=1)
corpusArrayFeaturesClassified_vec = vectorizer.fit_transform(corpusArrayFeaturesFull+corpusArrayFeaturesClassified+topicModelArray)
#print "corpusArrayFeaturesClassified_vec=%s" % str(corpusArrayFeaturesClassified_vec.shape)

topicModelArray_vec=vectorizer.transform(corpusArrayFeaturesClassified+topicModelArray)

#print "topicModelArray_Vec=%s" % str(topicModelArray_vec.shape)
#print "labels_corpusArrayFeaturesClassified_target=%s" % len(labels_corpusArrayFeaturesClassified_target)
#print "corpusArrayFeaturesClassified_vec=%s" % str(corpusArrayFeaturesClassified_vec.shape)
#print "corpusArrayFeaturesClassified len =%s" % len(corpusArrayFeaturesClassified)


#classifying data
classifier = (Perceptron(n_iter=50))
classifier.fit(topicModelArray_vec, labels_corpusArrayFeaturesClassified_target)

y = classifier.predict(corpusArrayFeaturesClassified_vec[0:200])

###### use CORPUS_CHUNK_FILE file
fj = open(CORPUS_CHUNK_FILE,'r')
ftext = open('predictunclassified.txt','w')
articlesdata = json.load(fj)
fj.close()

###### predict2, this has all new predictions.
for xitem in xrange(len(articlesdata["articles"])):
     articlesdata["articles"][xitem]["predict_one"]=y[xitem]
     articlesdata["articles"][xitem]["predict_one_copy"]=y[xitem]
     ftext.write("%s) topic=%s = predict=%s   predict_one=%s     predict_user1=%s" % (xitem, articlesdata["articles"][xitem]["topic"], articlesdata["articles"][xitem]["predict"],articlesdata["articles"][xitem]["predict_one"],articlesdata["articles"][xitem]["predict_user1"]))
ftext.close()

#writing back to the file
jsonFile = open(CORPUS_CHUNK_FILE, "w+")
jsonFile.write(json.dumps(articlesdata))
jsonFile.close()

