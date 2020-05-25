# Corax
### A RavenDB-Connector for PHP utilizing the REST-API

Corax is an extremely small, independent library for PHP offering you various methods for writing and retrieving data from a RavenDB instance.

### Attention:
This library is not finished by any means necessary. If you stumble across it for whatever reason, please keep that in mind and for the love of god, please don't use it productively yet. Give me a few weeks.

#### Requirements:

- PHP > 7.4
- PHP cURL Extension
- PHP YAML Extension
- OpenSSL
- An installed RavenDB instance

#### Usage:

Opening a Corax-Instance is fairly simple:
```
$corax = new Corax('path/to/config.yaml');
```

There are a few things to note here:

1. The server URL has to be the same as the one you'd use in your browser to access RavenDB Studio. Corax builds the necessary URLs by itself.
2. By default, RavenDB provides a .pfx file as client certificate. This is NOT compatible with cURL. You have to convert that .pfx file into a .pem file. This can be done with OpenSSL executing the command ``openssl pkcs12 -in your_certfile.pfx -out your_new_shiny_certfile.pem -clcerts``. OpenSSL will ask for a keyphrase, you can either omit that or use, whatever you prefer.

Corax currently supports 4 operations:

- Reading all documents (with startWith/pageSize params)
- Reading documents by IDs
- Creating new documents
- Deleting documents

### Example usage

###### Fetch all documents:
``$corax->getAllDocuments(5, 10);``

Corax will fetch all the documents, starting with the 5th, and returning up to 10 documents.

###### Fetch documents by ID:
``$corax->getDocumentById(["testdata/1", "testdata/2"]);``

Corax will fech the documents with the ID "testdata/1" and "testdata/2". Please not that, even if you only want one document, you still have to hand an array into the function.

###### Creating a new document:
``$corax->putDocument("testdata/99", ['name' => 'Jeff']);``

Creates a new document with the Id "testdata/99" and the content handed in the second param. Please note, you will always have to hand an array into the function. Corax will perform the JSON-Encoding itself.

###### Deleting a document
``$corax->deleteDocument("testdata/1);``

Does exactly what you'd think it does.

There's more to come. Stay tuned!