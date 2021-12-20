| Routes | Nom de la route | Méthodes (HTTP) | Controller | ->méthode() |
|--------|-----------------|-----------------|------------|-------------|
|/api/v1/tvshows        |       api_tvshows_list          |   GET             |       App\Controller\Api\TvShowController      |      index      |
|/api/v1/tvshows/{id}   |       api_tvshows_show           |   GET             |       App\Controller\Api\TvShowController      |      show       |
|/api/v1/tvshows   |       api_tvshows_add           |   POST            |           App\Controller\Api\TvShowController  |       add      |
|/api/v1/tvshows/{id}   |       api_tvshows_update           |   PUT            |           App\Controller\Api\TvShowController  |       update      |
|/api/v1/tvshows/{id}   |       api_tvshows_delete           |   DELETE            |           App\Controller\Api\TvShowController  |       delete      |
|/api/v1/categories     |       api_categories_list        |    GET             |       App\Controller\Api\CategoriyController |      index       |
|/api/v1/categories/{id}   |       api_categories_show           |   GET             |       App\Controller\Api\CategoriyController      |      show       |
|/api/v1/categories   |       api_categories_add           |   POST            |           App\Controller\Api\CategoriyController  |       add      |
|/api/v1/categories/{id}   |       api_categories_update           |   PUT            |           App\Controller\Api\CategoriyController  |       update      |
|/api/v1/categories/{id}   |       api_categories_delete           |   DELETE            |           App\Controller\Api\CategoriyController  |       delete      |
|/api/v1/characters     |       api_characters_list        |    GET             |       App\Controller\Api\CharacterController |      index       |
|/api/v1/characters/{id}   |       api_characters_show           |   GET             |       App\Controller\Api\CharacterController      |      show       |
|/api/v1/characters   |       api_characters_add           |   POST            |           App\Controller\Api\CharacterController  |       add      |
|/api/v1/characters/{id}   |       api_characters_update           |   PUT            |           App\Controller\Api\CharacterController  |       update      |
|/api/v1/characters/{id}   |       api_characters_delete           |   DELETE            |           App\Controller\Api\CharacterController  |       delete      |