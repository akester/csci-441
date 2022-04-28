#include <iostream> // IO stream. cin, cout, cerr, etc.
#include <fstream>  // IO stream class to operate on files. 
#include <iomanip>  // IO Manipulators.
#include <string>
#include <cstdlib>
#include <sstream> // rdbuf
#include <locale> // for isdigit function from c
#include "funcDef.h"
#include "funcDef2.h"
using namespace std;


int main(){

cout << "\t This program will correct and format the content of the file TOC.txt" << endl;
cout << "\t the next goal is to insert the content into the metadata.txt and"     << endl;
cout << "\t and apply the metadata.txt back to the pdf file."                     << endl;

// used to get user choice of operation. default value is quit
char choice = 'q';

// get the input from the file TOC.txt
string fileName;
cout << "Type the name of the TOC file: ";
getline(cin, fileName);
string fileContent;
string line;
ifstream readByLine(fileName);

while (getline (readByLine, line)) fileContent += line + "\n";
readByLine.close();
printMenu();
cout << "\t Please choose from menu: ";
choice = getUserInput_char();
  do{  
      switch (choice){
          // before add metadata into Toc
          case 'a':     addEmptySpaces(fileContent); break; // Add empty space       
          case 'b':        replaceDots(fileContent); break; // remove dots ... 
          case 'c': replaceEmptySpaces(fileContent); break; // remove several empty spaces
          case 'd':           eraseEol(fileContent); break; // remove EOL
          case 'e':          insertEol(fileContent); break; // Insert EOL
          case 'm':        addMetadata(fileContent); break; // add the metadata lines
          // after metadata has been inserted
          case 'f':   fixBMpagenumbers(fileContent); break; // 
          case 'g':         remPageNum(fileContent); break; // remove page number from the end of BookmarkTitle line
          case 'h':  remSubchaptersNum(fileContent); break;
          case 'l':      changeBMlevel(fileContent); break;
          // This can be used anytime
          case 'p':   printFileContent(fileContent); break; // print fileContent 
          case 's':         saveToFile(fileContent); break; // save fileContent variable to a file 
          case 'r':    rewriteMetadata(fileContent); break;
          case 'x':     extMetadataPDF()           ; break; // extract metadata information from pdf file,
          case 'i':   insMetadataToPdf()           ; break;
          case 'u':          printMenu()           ; break;
          case 'q':                                  break; // quit
          default : cout << "wrong option. Please check menu\n"; break;
          
      }
      cout << "\tpress 'u' to print the show menu again.";
      cout << "\tNext action: ";
      // choose the next action
      choice = getUserInput_char();
  } while (choice != 'q');
  return 0;
} //ends main function

