<?php

namespace PouleR\AppleMusicAPI;

use PouleR\AppleMusicAPI\Entity\LibraryResource;
use PouleR\AppleMusicAPI\Request\LibraryPlaylistCreationRequest;
use PouleR\AppleMusicAPI\Request\LibraryResourceAddRequest;

/**
 * Class AppleMusicAPI
 */
class AppleMusicAPI
{
    /**
     * @var APIClient
     */
    protected $client;

    /**
     * @param APIClient $client
     */
    public function __construct(APIClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return APIClient
     */
    public function getAPIClient()
    {
        return $this->client;
    }

    /**
     * @param string $developerToken
     */
    public function setDeveloperToken($developerToken)
    {
        $this->client->setDeveloperToken($developerToken);
    }

    /**
     * @return string
     */
    public function getDeveloperToken()
    {
        return $this->client->getDeveloperToken();
    }

    /**
     * @param string $musicUserToken
     */
    public function setMusicUserToken($musicUserToken)
    {
        $this->client->setMusicUserToken($musicUserToken);
    }

    /**
     * Fetch a single storefront by using its identifier.
     * https://developer.apple.com/documentation/applemusicapi/get_a_storefront
     *
     * @param string $id The identifier (an ISO 3166 alpha-2 country code) for the storefront you want to fetch.
     * @param string $include A comma separated list of additional relationships to include in the fetch.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function getStorefront(string $id, string $include = '')
    {
        $requestUrl = sprintf('storefronts/%s?include=%s', $id, $include);

        return $this->client->apiRequest('GET', $requestUrl);
    }

    /**
     * Fetch all the storefronts in alphabetical order.
     * https://developer.apple.com/documentation/applemusicapi/get_all_storefronts
     *
     * @param string $include A comma separated list of additional relationships to include in the fetch.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function getAllStorefronts(string $include = '')
    {
        $requestUrl = sprintf('storefronts?include=%s', $include);

        return $this->client->apiRequest('GET', $requestUrl);
    }

    /**
     * Fetch one or more charts from the Apple Music Catalog.
     * https://developer.apple.com/documentation/applemusicapi/get_catalog_charts
     *
     * @param string $storefront An iTunes Store territory, specified by an ISO 3166 alpha-2 country code.
     * @param array  $types  The possible values are albums, songs, and music-videos
     * @param string $genre  The identifier for the genre to use in the chart results.
     * @param int    $limit  The number of resources to include per chart.
     *                       The default value is 20 and the maximum value is 50.
     * @param int    $offset Only appears when chart is specified) The next page or group of objects to fetch.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function getCatalogCharts($storefront, $types = [], $genre = '', $limit = 20, $offset = 0)
    {
        $query = [
            'types' => implode(',', $types),
            'offset' => $offset,
            'limit' => $limit,
        ];

        if (!empty($genre)) {
            $query['genre'] = $genre;
        }

        $requestUrl = sprintf('catalog/%s/charts?%s', $storefront, http_build_query($query));

        return $this->client->apiRequest('GET', $requestUrl);
    }

    /**
     * Fetch a playlist by using its identifier.
     * https://developer.apple.com/documentation/applemusicapi/get_a_catalog_playlist
     *
     * @param string $storefront An iTunes Store territory, specified by an ISO 3166 alpha-2 country code.
     * @param string $playlistId The unique identifier for the playlist.
     * @param string $include A comma separated list of additional relationships to include in the fetch.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function getCatalogPlaylist(string $storefront, string $playlistId, string $include = '')
    {
        $requestUrl = sprintf('catalog/%s/playlists/%s?include=%s', $storefront, $playlistId, $include);

        return $this->client->apiRequest('GET', $requestUrl);
    }

    /**
     * Fetch an album by using its identifier.
     * https://developer.apple.com/documentation/applemusicapi/get_a_catalog_album
     *
     * @param string $storefront An iTunes Store territory, specified by an ISO 3166 alpha-2 country code.
     * @param string $albumId The unique identifier for the album.
     * @param string $include A comma separated list of additional relationships to include in the fetch.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function getCatalogAlbum(string $storefront, string $albumId, string $include = '')
    {
        $requestUrl = sprintf('catalog/%s/albums/%s?include=%s', $storefront, $albumId, $include);

        return $this->client->apiRequest('GET', $requestUrl);
    }
    
    /**
     * Fetch albums by a UPC.
     * https://developer.apple.com/documentation/applemusicapi/get_multiple_catalog_albums_by_upc
     *
     * @param string $storefront An iTunes Store territory, specified by an ISO 3166 alpha-2 country code.
     * @param string $upc An Universal Product Code.
     * @param string $include A comma separated list of additional relationships to include in the fetch.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function getMultipleCatalogAlbumsByUpc(string $storefront, string $upc, string $include = '')
    {
        $requestUrl = sprintf('catalog/%s/albums?filter[upc]=%s&include=%s', $storefront, $upc, $include);

        return $this->client->apiRequest('GET', $requestUrl);
    }

    /**
     * Fetch a song by using its identifier.
     * https://developer.apple.com/documentation/applemusicapi/get_a_catalog_song
     *
     * @param string $storefront An iTunes Store territory, specified by an ISO 3166 alpha-2 country code.
     * @param string $songId The unique identifier for the song.
     * @param string $include A comma separated list of additional relationships to include in the fetch.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function getCatalogSong(string $storefront, string $songId, string $include = '')
    {
        $requestUrl = sprintf('catalog/%s/songs/%s?include=%s', $storefront, $songId, $include);

        return $this->client->apiRequest('GET', $requestUrl);
    }

    /**
     * Fetch songs by an ISRC.
     * https://developer.apple.com/documentation/applemusicapi/get_multiple_catalog_songs_by_isrc
     *
     * @param string $storefront An iTunes Store territory, specified by an ISO 3166 alpha-2 country code.
     * @param string $isrc An unique International Standard Recording Code.
     * @param string $include A comma separated list of additional relationships to include in the fetch.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function getMultipleCatalogSongsByIsrc(string $storefront, string $isrc, string $include = '')
    {
        $requestUrl = sprintf('catalog/%s/songs?filter[isrc]=%s&include=%s', $storefront, $isrc, $include);

        return $this->client->apiRequest('GET', $requestUrl);
    }

    /**
     * Fetch an artist by using the artist's identifier.
     * https://developer.apple.com/documentation/applemusicapi/get_a_catalog_artist
     *
     * @param string $storefront An iTunes Store territory, specified by an ISO 3166 alpha-2 country code.
     * @param string $artistId The unique identifier for the artist.
     * @param string $include A comma separated list of additional relationships to include in the fetch.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function getCatalogArtist(string $storefront, string $artistId, string $include = '')
    {
        $requestUrl = sprintf('catalog/%s/artists/%s?include=%s', $storefront, $artistId, $include);

        return $this->client->apiRequest('GET', $requestUrl);
    }

    /**
     * Fetch a curator by using the curator's identifier.
     * https://developer.apple.com/documentation/applemusicapi/get_a_catalog_curator
     *
     * @param string $storefront An iTunes Store territory, specified by an ISO 3166 alpha-2 country code.
     * @param string $curatorId The unique identifier for the curator.
     * @param string $include A comma separated list of additional relationships to include in the fetch.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function getCatalogCurator(string $storefront, string $curatorId, string $include)
    {
        $requestUrl = sprintf('catalog/%s/curators/%s?include=%s', $storefront, $curatorId, $include);

        return $this->client->apiRequest('GET', $requestUrl);
    }

    /**
     * Get a Catalog Curator's Relationship Directly by Name
     * https://developer.apple.com/documentation/applemusicapi/get_a_catalog_curator_s_relationship_directly_by_name
     *
     * @param string $storefront
     * @param string $curatorId
     * @param string $relationship
     * @param string $include A comma separated list of additional relationships to include in the fetch.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function getCatalogCuratorRelationship(string $storefront, string $curatorId, string $relationship = 'playlists', int $limit = 10, int $offset = 0, string $include = '')
    {
        if ($relationship !== 'playlists') {
            throw new AppleMusicAPIException('Invalid relationship given, only \'playlists\' is allowed at the moment.');
        }

        $requestUrl = sprintf(
            'catalog/%s/curators/%s/%s?%s&include=%s',
            $storefront,
            $curatorId,
            $relationship,
            $include,
            $this->getLimitOffsetQueryString($limit, $offset)
        );

        return $this->client->apiRequest('GET', $requestUrl);
    }

    /**
     * Fetch a user’s storefront.
     * https://developer.apple.com/documentation/applemusicapi/get_a_user_s_storefront
     *
     * @param string $include A comma separated list of additional relationships to include in the fetch.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function getUsersStorefront(string $include = '')
    {
        $requestUrl = sprintf('me/storefront?include=%s', $include);

        return $this->client->apiRequest('GET', $requestUrl);
    }

    /**
     * Fetch the recently played resources for the user.
     * https://developer.apple.com/documentation/applemusicapi/get_recently_played_resources
     *
     * @param int $limit The limit on the number of objects, or number of objects in the specified relationship,
     *                   that are returned.
     * @param int $offset The next page or group of objects to fetch.
     * @param string $include A comma separated list of additional relationships to include in the fetch.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function getRecentlyPlayedResources(int $limit = 5, int $offset = 0, string $include = '')
    {
        $requestUrl = sprintf('me/recent/played?%s&include=%s', $this->getLimitOffsetQueryString($limit, $offset, 10), $include);

        return $this->client->apiRequest('GET', $requestUrl);
    }

    /**
     * Fetch all the library playlists in alphabetical order.
     * https://developer.apple.com/documentation/applemusicapi/get_all_library_playlists
     *
     * @param int $limit
     * @param int $offset
     * @param string $include A comma separated list of additional relationships to include in the fetch.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function getAllLibraryPlaylists(int $limit = 25, int $offset = 0, string $include = '')
    {
        $requestUrl = sprintf('me/library/playlists?%s&include=%s', $this->getLimitOffsetQueryString($limit, $offset), $include);

        return $this->client->apiRequest('GET', $requestUrl);
    }

    /**
     * Fetch all the library albums in alphabetical order.
     * https://developer.apple.com/documentation/applemusicapi/get_all_library_albums
     *
     * @param int $limit
     * @param int $offset
     * @param string $include A comma separated list of additional relationships to include in the fetch.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function getAllLibraryAlbums(int $limit = 25, int $offset = 0, string $include = '')
    {
        $requestUrl = sprintf('me/library/albums?%s&include=%s', $this->getLimitOffsetQueryString($limit, $offset), $include);

        return $this->client->apiRequest('GET', $requestUrl);
    }

    /**
     * Fetch all the library artists in alphabetical order.
     * https://developer.apple.com/documentation/applemusicapi/get_all_library_artists
     *
     * @param int $limit
     * @param int $offset
     * @param string $include A comma separated list of additional relationships to include in the fetch.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function getAllLibraryArtists(int $limit = 25, int $offset = 0, string $include = '')
    {
        $requestUrl = sprintf('me/library/artists?%s&include=%s', $this->getLimitOffsetQueryString($limit, $offset), $include);

        return $this->client->apiRequest('GET', $requestUrl);
    }

    /**
     * Fetch all the library music videos in alphabetical order.
     * https://developer.apple.com/documentation/applemusicapi/get_all_library_music_videos
     *
     * @param int $limit
     * @param int $offset
     * @param string $include A comma separated list of additional relationships to include in the fetch.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function getAllLibraryMusicVideos(int $limit = 25, int $offset = 0, string $include = '')
    {
        $requestUrl = sprintf('me/library/music-videos?%s&include=%s', $this->getLimitOffsetQueryString($limit, $offset), $include);

        return $this->client->apiRequest('GET', $requestUrl);
    }

    /**
     * Add a catalog resource to a user’s iCloud Music Library.
     * https://developer.apple.com/documentation/applemusicapi/add_a_resource_to_a_library
     *
     * @param LibraryResourceAddRequest $addRequest
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function addResourceToLibrary(LibraryResourceAddRequest $addRequest)
    {
        $requestParameters = [];

        foreach ($addRequest->getSongs() as $song) {
            $this->appendResourceToRequest($requestParameters, $song);
        }

        foreach ($addRequest->getAlbums() as $album) {
            $this->appendResourceToRequest($requestParameters, $album);
        }

        foreach ($addRequest->getMusicVideos() as $musicVideo) {
            $this->appendResourceToRequest($requestParameters, $musicVideo);
        }

        foreach ($addRequest->getPlaylists() as $playlist) {
            $this->appendResourceToRequest($requestParameters, $playlist);
        }

        $requestUrl = sprintf(
            'me/library?%s',
            urldecode(http_build_query(['ids' => $requestParameters]))
        );

        return $this->client->apiRequest('POST', $requestUrl, [], ' ');
    }

    /**
     * Create a New Library Playlist
     * https://developer.apple.com/documentation/applemusicapi/create_a_new_library_playlist
     *
     * @param LibraryPlaylistCreationRequest $playlist
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function createLibraryPlaylist(LibraryPlaylistCreationRequest $playlist)
    {
        $requestBody['attributes'] = [
            'name' => $playlist->getName(),
            'description' => $playlist->getDescription(),
        ];

        foreach ($playlist->getTracks() as $track) {
            $requestBody['relationships']['tracks']['data'][] = [
                'id' => $track->getId(),
                'type' => $track->getType(),
            ];
        }

        return $this->client->apiRequest(
            'POST',
            'me/library/playlists',
            [
                'Content-Type' => 'application/json',
            ],
            json_encode($requestBody)
        );
    }

    /**
     * Add Tracks to a Library Playlist
     * https://developer.apple.com/documentation/applemusicapi/add_tracks_to_a_library_playlist
     *
     * @param string            $playlistId
     * @param LibraryResource[] $tracks A list of dictionaries with information about the tracks to add.
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function addTracksToLibraryPlaylist($playlistId, array $tracks)
    {
        $requestBody = [];

        foreach ($tracks as $track) {
            if (!$track instanceof LibraryResource) {
                throw new AppleMusicAPIException('Invalid track');
            }

            $requestBody['data'][] = [
                'id' => $track->getId(),
                'type' => $track->getType(),
            ];
        }

        return $this->client->apiRequest(
            'POST',
            sprintf('me/library/playlists/%s/tracks', $playlistId),
            [
                'Content-Type' => 'application/json',
            ],
            json_encode($requestBody)
        );
    }

    /**
     * Search for a catalog resource
     * https://developer.apple.com/documentation/applemusicapi/search_for_catalog_resources
     * https://api.music.apple.com/v1/catalog/us/search?term=caldonia&types=songs
     *
     * @param string $storefront An iTunes Store territory, specified by an ISO 3166 alpha-2 country code.
     * @param string $searchTerm The entered text for the search with ‘+’ characters between each word,
     *                           to replace spaces
     * @param string $searchTypes The list of the types of resources to include in the results. artists,albums,songs
     *
     * @return array|object
     *
     * @throws AppleMusicAPIException
     */
    public function searchCatalog($storefront, $searchTerm, $searchTypes)
    {
        $requestUrl = sprintf('catalog/%s/search?term=%s&types=%s', $storefront, $searchTerm, $searchTypes);

        return $this->client->apiRequest('GET', $requestUrl);
    }

    /**
     * @param string $storefront
     * @param string $albumId
     *
     * @return array containing songIds for the given album
     *
     * @throws AppleMusicAPIException
     */
    public function getSongIdsForAlbum($storefront, $albumId)
    {
        $album = $this->getCatalogAlbum($storefront, $albumId);

        if (!isset($album->data[0]->relationships->tracks->data)) {
            throw new AppleMusicAPIException(sprintf('Invalid response for album with id "%s".', $albumId));
        }

        $songIds = [];
        foreach ($album->data[0]->relationships->tracks->data as $track) {
            if (!isset($track->id)) {
                continue;
            }

            $songIds[] = $track->id;
        }

        return $songIds;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @param int $maxLimit
     *
     * @return string
     */
    private function getLimitOffsetQueryString($limit, $offset, $maxLimit = 100)
    {
        return http_build_query([
            'offset' => $offset,
            'limit' => min($limit, $maxLimit),
        ]);
    }

    /**
     * @param array           $requestParameters
     * @param LibraryResource $resource
     */
    private function appendResourceToRequest(array &$requestParameters, LibraryResource $resource)
    {
        if (array_key_exists($resource->getType(), $requestParameters)) {
            $requestParameters[$resource->getType()] .= ','.$resource->getId();
        } else {
            $requestParameters[$resource->getType()] = $resource->getId();
        }
    }
}
