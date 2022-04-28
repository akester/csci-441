#include <iostream>
#include <fstream>
#include <string>
#include <sstream>
#include "funcDef2.h"
#include "funcDef.h"
using namespace std;

/*---------------------------------------------------------------------------*/
void remPageNum(string& str){ 
    string bmt = "BookmarkTitle:";
    long unsigned int n=0;
    long unsigned int pos = 0;
    while (pos < str.size()){
        pos = n;
        n = str.find(bmt, pos);      // n is at the beginning of the line
        if ( n != string::npos){
            n = str.find('\n', n);   // n is now at the end of the line
            if ( n != string::npos){
                pos= n;              // pos will backtrack until the closest occurence of space
                while ( (str[pos] != ' ') && (pos < str.size()) ) pos-=1; 
                str.erase(pos,n-pos);
            }
        }
    }
}
/*---------------------------------------------------------------------------*/
void remSubchaptersNum(string& str){
// This will replace the sub-chapters numbers by empty spaces
// e.g:  BookmarkTitle: 2.1.3 Requirements ->  BookmarkTitle:      Requirements
    string bmt = "BookmarkTitle:";
    long unsigned int pos = 0;
    long unsigned int n = 0;
    while (pos < str.size()){ 
        n = str.find(bmt, pos);
        if (n != string::npos){
            if (str[n+16]=='.' && str[n+18]=='.'){
                str.replace(n+15,5,"    ");
                pos = n+1;
            }  
        }
        pos +=1;
    }
}
/*---------------------------------------------------------------------------*/
void fixBMpagenumbers(string& str){
    
    // ask user for overhead value and starting page number
    cout << "Enter overhead: ";
    int overhead = getUserInput_int();
    cout << "starting at page: ";
    int startPos = getUserInput_int();

    //main variables
    string bmpn = "BookmarkPageNumber:";
    string strOut;
    istringstream issStr(str);
    ostringstream ossStr(strOut);

    // start looping
    string line;
    while (getline(issStr, line)){
        string word;
        istringstream issLine(line);
        //isBmpn = false;        
        while (issLine >> word){
            // do operations if bmpn otherwise print the line
            if (word.compare(bmpn)==0) {
                ossStr << word << " "; 
                string word2;
                issLine >> word;
                if (stoi(word)>= startPos){
                    int pageNumber = stoi(word) + overhead;
                    ossStr << pageNumber << endl;
                }else ossStr << word << endl;
                
            // just print the line if it's not a BookmarkPageNumber
            }else{
                ossStr << line << endl;
                break; // hopefully it will skip this inner while
            }          
        }
    }
    // return the results
   str.clear();
   str = ossStr.str();
}
/*---------------------------------------------------------------------------*/
void extMetadataPDF(){
    string pdfFileName;
    cout << "Type the pdf file name: ";
    cin >> pdfFileName;
    string str = "pdftk "+ pdfFileName +" dump_data> metadata.txt";

    // Convert string to const char * as system requires
    // parameter of type const char *
    const char *command = str.c_str();

    cout << "Extracting metadata from " << pdfFileName << endl;
    system(command);
}
/*---------------------------------------------------------------------------*/
void rewriteMetadata(string& str){
    // toc is in str. the objective is to merge str with metadata file.

    // open the metadata file for read
    ifstream metadataIn("metadata.txt");

    // create a variable to hold metadata merged with str.
    ostringstream ossStr;

    // start to copy line by line of the metadata to temp variable
    string line;
    string word;
    while (getline (metadataIn, line)){
        //transfer line by line of the metadata to temp variable
        ossStr << line << endl;
        istringstream issLine(line);
        issLine >> word;
        // find the line NumberOfPages:
        if (word.compare("NumberOfPages:")==0){
            // start to insert Toc into metadata
            string lineSrt;
            istringstream issStr(str);
            while (getline(issStr, lineSrt)){
                ossStr << lineSrt << endl;
            }
        }
    }
    metadataIn.close();
    // erase metadata.txt and reopen to write
    ofstream metadataOut("metadata.txt");
    // copy the temp to metadata.txt
    metadataOut << ossStr.str();
    metadataOut.close();
}
/*---------------------------------------------------------------------------*/
void insMetadataToPdf(){

    string namePdfFile;
    cout << "Type the name of the pdf file: ";
    cin >> namePdfFile;
    string namePdfFileOutput =  namePdfFile + "out.pdf";

    string str = "pdftk "+ namePdfFile +" update_info_utf8 metadata.txt output "+ namePdfFileOutput;

    // Convert string to const char * as system requires
    // parameter of type const char *
    const char *command = str.c_str();

    system(command);
    cout << "metadata.txt inserted on pdf file." << endl;
}
/*---------------------------------------------------------------------------*/
void changeBMlevel(string& str){

    // go over str line by line
    // verify the first word on each line
    // if word is bookmarkTitle: 
        // Look for the pattern on the second word
              // Match:     change the bookmark
              // not match: leave as it is
    // else (word is not bookmarkTitle) leave as it is

    // READ STR LINE BY LINE
    //istringstream strOut; // this will hold the str after modifications
    istringstream issStr(str); // getline will read a stream of str
    ostringstream ossStr; // PRECISA DO STROUT??????????????????????????????????????
    cout << "fora do loop" << endl;
    int bookmarkLevel = 1;
    string line;
    while (getline(issStr, line)){
        cout << "inside do loop" << endl;
        string word; 
        istringstream issLine(line);     // used to navigate throught a line
        issLine >> word;                 // first word in the line
        // CHECK FOR THE PATTERN HERE                                            
        if (word.compare("BookmarkTitle:")==0){
            cout << "first comparison ";
            issLine >> word;             // second word in the line
            //if the second word in the line contain any dots bmkl will be 2
            if (word.find('.') != string::npos){ 
                 bookmarkLevel = 2;
                 cout << " word.find ";
            }else bookmarkLevel = 1;
        }
        // paste line to the temp variable
        if (word.compare("BookmarkLevel:")==0){
            cout << "imprime" << endl;
            ossStr << word << " " << bookmarkLevel << endl;
        }else{ ossStr << line << endl; cout << endl;}
    }

    // replace the content of str by the content in the temp variable ossStr
    str.clear();
    str = ossStr.str();
}