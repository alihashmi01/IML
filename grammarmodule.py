#!/usr/bin/python
# -*- coding: utf-8 -*-

################################################################################
#
################################################################################

import couchdb
import nltk
from nltk import Text
from nltk import TextCollection
import math
import re
import time
import string
import sys
import datetime
from datetime import datetime
from nltk.tokenize import RegexpTokenizer
from nltk import bigrams, trigrams
import timeit
import gensim
from gensim import corpora, models, similarities
import logging, gensim, bz2

import logging
import numpy as np
from optparse import OptionParser
import sys
from time import time
import pylab as pl

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


class GrammarModule:

    def __init__(self):
        self.tokenizer = nltk.WordPunctTokenizer()#nltk.RegexpTokenizer("[\w]", flags=re.UNICODE)
        self.stopwords = self.getStopWordList('stop-words-english4.txt')
        #https://gist.github.com/alexbowe/
        self.sentence_re = r'''(?x)
              ([A-Z])(\.[A-Z])+\.?
            | \w+(-\w+)*
            | \$?\d+(\.\d+)?%?
            | \.\.\.
            | [][.,;"'?():-_`]
        '''
        # Grammar from this paper http://lexitron.nectec.or.th/public/COLING-2010_Beijing_China/PAPERS/pdf/PAPERS065.pdf
        self.grammar = r"""
            NBAR:
                {<NN.*|JJ>*<NN.*>}  #

            NP:
                {<NBAR>}
                {<NBAR><IN><NBAR>}
        """
        self.chunker = nltk.RegexpParser(self.grammar)
        self.toks = ""
        self.postoks = ""
        self.lemmatizer = nltk.WordNetLemmatizer()
        self.stemmer = nltk.stem.porter.PorterStemmer()
        self.tree = ""
        #self.print_cities()

    def leaves(self,tree):
        #NP leaf node of tree
        for childtree in tree.subtrees(filter = lambda t: t.node=='NP'):
            yield childtree.leaves()

    def lemmatize_word(self,word,lemma, stemm, lowercase):
        # this results in unsusual words, thistl instead of thistle
        if lemma == 1:
            word = self.lemmatizer.lemmatize(word)
        if stemm == 1:
            word = self.stemmer.stem_word(word)
        if lowercase == 1:
            word = word.lower()
        return word

    def filterStopWords(self,word):
        blnIncludeWord = bool( len(word) > 1
            and word.lower().decode('utf-8') not in self.stopwords)
        return blnIncludeWord

    def get_terms(self):
        for leaf in self.leaves(self.tree):
            term = [ self.lemmatize_word(w,0,0,0) for w,t in leaf if self.filterStopWords(w)]
            yield term

    #this will generate a noune phrase term string
    def get_str_entities(self,txtPassString):
        self.toks = nltk.regexp_tokenize(txtPassString, self.sentence_re)
        self.postoks = nltk.tag.pos_tag(self.toks)
        self.tree = self.chunker.parse(self.postoks)
        strTermsString = ""
        arrayterms = self.get_terms()
        for singlearray in arrayterms:
            for oneterm in xrange(len(singlearray)):
                strTermsString = strTermsString + " " + re.sub('cq$', '', re.sub('[,.?!\t\n\_\%\$ ]+', '', singlearray[oneterm]))
        print strTermsString
        return strTermsString

    #to track the history of the run
    def getTimeStamp(self, inputstartend):
        FORMAT = '%Y-%m-%d %H:%M:%S'
        data = "\n" + inputstartend + ' ' + datetime.now().strftime(FORMAT)
        with open("trackhistoryfile.txt", "a") as trackfile:
            trackfile.write(data)

    def getStopWordList(self,stopWordListFileName):
        #read the stopwords file and build a list
        stopWords = []
        stopWords.append('Boston.com')
        stopWords.append('Your Town')
        stopWords.append('Boston Globe')
        fp = open(stopWordListFileName, 'r')
        line = fp.readline()
        while line:
            word = line.strip()
            stopWords.append(word.decode('utf-8'))
            line = fp.readline()
        fp.close()
        if stopWords:
            stopWords.sort()
            last = stopWords[-1]
            for i in range(len(stopWords)-2, -1, -1):
                if last == stopWords[i]:
                    del stopWords[i]
                else:
                    last = stopWords[i]
        return stopWords

    def getStopWords(self):
        return self.stopwords


'''
w = WordFreqencyJob()
str = []
str.append(w.get_str_entities("Civil engineering students gather at National Concrete Canoe Competition at University of Illinois at Urbana-Champaign, where they pit their designs for concrete canoes against one another; annual competition has been run by American Society of Civil Engineers for more than two decades.".decode("utf-8")))

str.append(w.get_str_entities("Columbia University is grappling with a rare faculty revolt against Feniosky Pena-Mora, the dean of its engineering school, who has been accused of ignoring professors’ concerns and worsening a chronic space shortage.".decode("utf-8")))

str.append(w.get_str_entities("An agreement has been reached between Iran and six world powers to curb Tehran's nuclear programme in exchange for limited sanctions relief.".decode("utf-8")))

str.append(w.get_str_entities("Egypt's interim president on Sunday banned public gatherings of more than 10 people without prior government approval, imposing hefty fines and prison terms for violators in a bid to stifle the near-constant protests roiling the country.".decode("utf-8")))

str.append(w.get_str_entities("Swiss voters rejected severe limits on executive pay Sunday in a ballot that nonetheless illustrated rising popular resentment toward corporate excess in one of Europe's most business-friendly countries.".decode("utf-8")))

str.append(w.get_str_entities("The outcome of this week's Vilnius Summit became known, unfortunately, a week ahead of schedule. The Ukrainian government decided to suspend the five-year old negotiations to secure an ambitious Ukraine-EU Association Agreement.".decode("utf-8")))

str.append(w.get_str_entities("One of the few bright spots in Hillary Clinton's tenure as secretary of state was the development of a strategic relationship with Morocco, a heightened level of cooperation on economic, social and security matters.".decode("utf-8")))

str.append(w.get_str_entities("Standout Chicago Bulls guard Derrick Rose will have knee surgery on Monday and could be sidelined for the rest of the season if he decides to have his torn right meniscus reattached.".decode("utf-8")))

str_target = [1,1,2,2,2,2,2,3]

print "***str="
print str
texts = [[word for word in document.split() ]
       for document in str]
all_tokens = sum(texts, [])
tokens_once = set(word for word in set(all_tokens) if all_tokens.count(word) == 1)
print "***tokens_once="
print tokens_once
print "***end tokens once"

print "***xts:start"
print texts
print "***texts:end"



#generate unique word items
dictionary = corpora.Dictionary(texts)
print '***dictionary'
for x in xrange(len(dictionary)):
    print dictionary[x]
    if x == 2: break

diction = {}
print dictionary.token2id
for ss in dictionary:
   diction[ss]=dictionary[ss]

print '***diction:start'
print diction
print '***diction: end'

#use text (documents in word vec form) to convert it into a corpus
corpus = [dictionary.doc2bow(text) for text in texts]


print("Extracting features from the training dataset using a sparse vectorizer")

vectorizer = TfidfVectorizer(sublinear_tf=True, max_df=0.5,
                                 stop_words='english', lowercase=0)

X_train = vectorizer.fit_transform(str)
print '***X_train get_feature_names'
print vectorizer.get_feature_names()

print '***X_train get_feature_names'
print vectorizer.get_feature_names()
print '***X_train'
print X_train.shape

#test string
Xn=[]
Xn.append(w.get_str_entities("The American engineering teams are working hard to solve national problems.".decode("utf-8")))
Xn.append(w.get_str_entities("The Chicago Bulls won the game.".decode("utf-8")))
Xn.append(w.get_str_entities("Iran is on the verge of collapse.".decode("utf-8")))
X=vectorizer.transform(Xn)
print "X="
print X.shape
#test
for clf, name in (
        (RidgeClassifier(tol=1e-2, solver="lsqr"), "Ridge Classifier"),
        (Perceptron(n_iter=50), "Perceptron"),
        (PassiveAggressiveClassifier(n_iter=50), "Passive-Aggressive"),
        (KNeighborsClassifier(n_neighbors=10), "kNN"),
        (LinearSVC(penalty="l1",dual=False, tol=1e-3),"LinearSVC"),
        (KNeighborsClassifier(n_neighbors=10),"kNN"),
        (MultinomialNB(),"MultinomialNB")
        ):
            clf.fit(X_train, str_target)
            print name
            print clf.predict(X)

print '***X_train'
print X_train[7]
arrayVec = []
print '***corpus'
corpora.MmCorpus.serialize('corpus.mm', corpus)
corpus = corpora.MmCorpus('corpus.mm')
print corpus
print "***type of corpus, followed by corpus"
print type(corpus)
print corpus[0]
print corpus[1]
print corpus[2]
print corpus[2]

tfidf = gensim.models.tfidfmodel.TfidfModel(corpus)
print tfidf[(0, 1.0), (1, 1.0), (2, 2.0), (3, 1.0), (4, 1.0), (5, 1.0), (6, 1.0), (7, 1.0), (8, 1.0), (9, 1.0), (10, 1.0), (11, 1.0), (12, 1.0), (13, 1.0), (14, 1.0), (15, 1.0), (16, 1.0), (17, 1.0)]
'''




