# Fetching Approach
The strategy design pattern has been applied here in which the `Clients` folder contain the strategies (client classes) each class is represent a client data sources and encapsulate it's opeartions.     

However the `FetchNewsService` is the context and it calls the clients that are configured within `config/api.php` config file.
