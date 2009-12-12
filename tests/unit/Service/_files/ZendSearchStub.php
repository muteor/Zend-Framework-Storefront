<?php
class Zend_Search_Stub implements Zend_Search_Lucene_Interface
{
    public function addDocument(Zend_Search_Lucene_Document $document) {
        ;
    }
    public function closeTermsStream() {
        ;
    }
    public function addReference() {
        ;
    }
    public function commit() {
        ;
    }
    public function count() {
        ;
    }
    public function currentTerm() {
        ;
    }
    public function delete($id) {
        ;
    }
    public function docFreq(Zend_Search_Lucene_Index_Term $term) {
        ;
    }
    public function find($query) {
        $a = new stdClass();
        $a->id = 1;
        return array($a);
    }
    public static function getActualGeneration(Zend_Search_Lucene_Storage_Directory $directory) {
        ;
    }
    public static function getDefaultSearchField() {
        ;
    }
    public function getDirectory() {
        ;
    }
    public function getDocument($id) {
        ;
    }
    public function getFieldNames($indexed=false ) {
        ;
    }
    public function getFormatVersion() {
        ;
    }
    public function getMaxBufferedDocs() {
        ;
    }
    public function getMaxMergeDocs() {
        ;
    }
    public function getMergeFactor() {
        ;
    }
    public static function getResultSetLimit() {
        ;
    }
    public static function getSegmentFileName($generation) {
        ;
    }
    public function getSimilarity() {
        ;
    }
    public function hasDeletions() {
        ;
    }
    public function hasTerm(Zend_Search_Lucene_Index_Term $term) {
        ;
    }
    public function isDeleted($id) {
        ;
    }
    public function maxDoc() {
        ;
    }
    public function nextTerm() {
        ;
    }
    public function norm($id, $fieldName) {
        ;
    }
    public function numDocs() {
        ;
    }
    public function optimize() {
        ;
    }
    public function removeReference() {
        ;
    }
    public function resetTermsStream() {
        ;
    }
    public static function setDefaultSearchField($fieldName) {
        ;
    }
    public function setFormatVersion($formatVersion) {
        ;
    }
    public function setMaxBufferedDocs($maxBufferedDocs) {
        ;
    }
    public function setMaxMergeDocs($maxMergeDocs) {
        ;
    }
    public function setMergeFactor($mergeFactor) {
        ;
    }
    public static function setResultSetLimit($limit) {
        ;
    }
    public function skipTo(Zend_Search_Lucene_Index_Term $prefix) {
        ;
    }
    public function termDocs(Zend_Search_Lucene_Index_Term $term, $docsFilter=null ) {
        return array(1);
    }
    public function termDocsFilter(Zend_Search_Lucene_Index_Term $term, $docsFilter=null ) {
        ;
    }
    public function termFreqs(Zend_Search_Lucene_Index_Term $term, $docsFilter=null ) {
        ;
    }
    public function termPositions(Zend_Search_Lucene_Index_Term $term, $docsFilter=null ) {
        ;
    }
    public function terms() {
        ;
    }
    public function undeleteAll() {
        ;
    }

}
