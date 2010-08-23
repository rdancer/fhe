// List recently-released Linux applications. (Written in C++.)
// For more details about O'Reilly's excellent Meerkat news service, see:
// http://www.oreillynet.com/pub/a/rss/2000/11/14/meerkat_xmlrpc.html */

#include <iostream>
#include <sstream>
#include <string>

using namespace std;

#include <xmlrpc-c/oldcppwrapper.hpp>

#define NAME           "XML-RPC C++ Meerkat Query Demo"
#define VERSION        "0.1"
#define MEERKAT_URL    "http://www.oreillynet.com/meerkat/xml-rpc/server.php"
#define SOFTWARE_LINUX (6)

static void
list_apps(unsigned int const hours) {

    // Build our time_period parameter.
    ostringstream time_period_stream;
    time_period_stream << hours << "HOUR";
    string const time_period(time_period_stream.str());

    // Assemble our meerkat query recipe.
    XmlRpcValue recipe = XmlRpcValue::makeStruct();
    recipe.structSetValue("category", XmlRpcValue::makeInt(SOFTWARE_LINUX));
    recipe.structSetValue("time_period", XmlRpcValue::makeString(time_period));
    recipe.structSetValue("descriptions", XmlRpcValue::makeInt(76));

    // Build our parameter array.
    XmlRpcValue param_array(XmlRpcValue::makeArray());
    param_array.arrayAppendItem(recipe);

    // Create a client pointing to Meerkat.
    XmlRpcClient meerkat(MEERKAT_URL);

    // Perform the query.
    XmlRpcValue const apps(meerkat.call("meerkat.getItems", param_array));

    // Print our results.
    size_t const app_count(apps.arraySize());
    bool first;
    size_t i;
    for (i = 0, first=false; i < app_count; ++i, first = false) {
        XmlRpcValue const app(apps.arrayGetItem(i));

        // Get some information about our application.
        string const title(app.structGetValue("title").getString());
        string const link(app.structGetValue("link").getString());
        string const description(
            app.structGetValue("description").getString());
    
        // Print a separator line if necessary.
        if (!first)
            cout << endl;

        // Print this application entry.
        if (description.size() > 0)
            cout << title << endl << description << endl << link << endl;
        else
            cout << title << endl << description << endl << link << endl;
    }
}



int
main(int argc, char **argv) {
    int retval;
    unsigned int hours;

    // Parse our command-line arguments.
    if (argc == 1) {
        hours = 25;
    } else if (argc == 2) {
        hours = atoi(argv[1]);
    }
    if (hours == 0) {
        cerr << "Hours must be positive" << endl;
        exit(1);
    }
    if (hours > 49) {
        cerr << "It's not nice to ask for > 49 hours at once." << endl;
        exit(1);    
    }

    // Start up our client library.
    XmlRpcClient::Initialize(NAME, VERSION);

    // Call our implementation, and watch out for faults.
    try {
        list_apps(hours);
        retval = 0;
    } catch (XmlRpcFault& fault) {
        cerr << argv[0] << ": XML-RPC fault #" << fault.getFaultCode()
             << ": " << fault.getFaultString() << endl;
        retval = 1;
    }

    // Shut down our client library.
    XmlRpcClient::Terminate();

    return retval;
}
