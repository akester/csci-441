#include <iostream> // IO stream. cin, cout, cerr, etc.
#include <fstream>  // IO stream class to operate on files. 
#include <iomanip>  // IO Manipulators.
#include <string>
#include <cstdlib> // atoi()
#include <sstream> // rdbuf
#include <locale> // for isdigit function from c
#include "funcDef.h"
using namespace std;


// ##########################################
// >>>>>>>> FUNCTIONS DEFINITION <<<<<<<<<<<<

/*---------------------------------------------------------------------------*/
void printMenu(){
// pre-condition: none
// pos-condition: the test menu is displayed for choose
    cout << endl;
    cout << "\t**************************************************************************" << endl;
    cout << "\t*  OPERATIONS MENU                                                       *" << endl;
    cout << "\t*  >>>>> Before Apply Metadata Lines:                                    *" << endl;
    cout << "\t*  a. Add space between sub_chapter numbers and titles                   *" << endl;    
    cout << "\t*  b. Replace consecutive dots by empty space                            *" << endl;
    cout << "\t*  c. Replace consecutive empty spaces by one empty space                *" << endl;
    cout << "\t*  d. Erase the End of Lines                                             *" << endl;
    cout << "\t*  e. Put Eol between line numbers and sub-chapter numbers e.g 34.1.5    *" << endl;
    cout << "\t*  m. Add metadata lines                                                 *" << endl;
    cout << "\t*  >>>>> After Apply  Metadata Lines:                                    *" << endl;
    cout << "\t*  f. Fix the page number in BookmarkPageNumber                          *" << endl;
    cout << "\t*  g. Remove page number at the end of BookmarkTitle line                *" << endl;
    cout << "\t*  h. Replace the SubChapters numbers of level 3 by empty spaces         *" << endl;
    cout << "\t*  l. Change the BookmarkLevel based on pattern (need to be programmend) *" << endl;
    cout << "\t*  >>>>> Anytime:                                                        *" << endl;
    cout << "\t*  p. Print variable fileContent                                         *" << endl;
    cout << "\t*  s. Save to a file named toc_out.txt                                   *" << endl;
    cout << "\t*  r. Rewrite the metadata.txt with the TOC                              *" << endl;
    cout << "\t*  x. extract metadata from pdf                                          *" << endl;
    cout << "\t*  i. insert metadata to pdf                                             *" << endl;
    cout << "\t*  q. Quit                                                               *" << endl;
    cout << "\t**************************************************************************" << endl;

}
/*---------------------------------------------------------------------------*/
char getUserInput_char(){
// User will define what operation will be performed, based on the menu
// pos-condition: return an char entered by user.
    char userInput = '0';   
    cin >> userInput;  
    while (cin.fail()){ 
        cin.clear();
        cin.ignore();
        cout << "Error. Try again. Enter a valid number: ";
        cin >> userInput;
    }  
return (userInput);
}
/*---------------------------------------------------------------------------*/
int  getUserInput_int(){
// User will inform overhead and page number for funcion fixBmpagenumber
// pos-condition: return an int entered by user.
    int userInput = 0;   
    cin >> userInput;  
    while (cin.fail()){ 
        cin.clear();
        cin.ignore();
        cout << "Error. Try again. Enter a valid number: ";
        cin >> userInput;
    }  
return (userInput);
}
/*---------------------------------------------------------------------------*/
void eraseEol(string& str){
    for (long unsigned int i = 0; i < str.size(); i++){
        if (str[i] == '\n'){
            str.erase(str.begin()+i);
        }
    }
}
/*---------------------------------------------------------------------------*/
void replaceDots(string& str){
    for (long unsigned int i = 0; i < str.size(); i++){
        if (str[i] == '.' && str[i+1]== '.'){
            long int p = 0;
            while (str[i+p]=='.'){
                p++;
            }
            str.replace(i,p," ");
        }
    }
}
/*---------------------------------------------------------------------------*/
void replaceEmptySpaces(string& str){
    for (long unsigned int i = 0; i < str.size(); i++){
        if (str[i] == ' ' && str[i+1]== ' '){
            long int p = 0;
            while (str[i+p]== ' '){
                p++;
            }
            str.replace(i,p," ");
        }
    }
}
/*---------------------------------------------------------------------------*/
void insertEol(string& str){
    for (long unsigned int i = 0; i < str.size(); i++){
        if (isdigit(str[i])){
            if ( str[i-1]!= '.' && str[i+1]=='.'){
                str.insert(i,"\n");
                i++;
            }
        }
    }
}
/*---------------------------------------------------------------------------*/
void addEmptySpaces(string& str){
    for (long unsigned int i = 0; i < str.size(); i++){
        if (isdigit(str[i])){
            if ( str[i-1]== '.' && isalpha(str[i+1])){
                cout << str[i] << endl;
                str.insert(i+1," ");
                i++;
            }
        }
    }
}
/*---------------------------------------------------------------------------*/
void saveToFile(string& str){
    ofstream NewFile("toc_out.txt"); //create and open a text file
    NewFile << str;
    NewFile.close();
}
/*---------------------------------------------------------------------------*/
void printFileContent (string& str){
    cout << str << endl;
}
/*---------------------------------------------------------------------------*/
void addMetadata(string& str){
    // open a exit file metadata.txt

    ofstream metadataFile ("toc_out.txt");
    string bmPageNumber = "1";                   //  page number
    //int bmOverhead = 0;                        // page number overhead
    string bmLevel = "1";                        //  1, 2 or 3
    string line;
    string word;
    string formatedLine;

    // make a string stream
    istringstream iss1(str);
    while (getline(iss1, line)){
            

        // dial with bookmark level version 2
        istringstream iss2(line);
        // deal with Page number
        while (iss2 >> word); // hoppefully after the loop, "word" will hold the last block of the string
        bmPageNumber = word;

        // Put the metadata format in line
        formatedLine += "BookmarkBegin\nBookmarkTitle: " + line + "\n";
        formatedLine += "BookmarkLevel: " + bmLevel + "\n";
        formatedLine += "BookmarkPageNumber: " + bmPageNumber;

        // white the formated line to the file
        metadataFile << formatedLine << endl;
        formatedLine = "";
    }
    metadataFile.close();
}
