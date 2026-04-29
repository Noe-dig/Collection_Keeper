<?php

/**
 * Fetches the front cover art URL for a given artist and album.
 * * @param string $artist Name of the artist
 * @param string $album Name of the album
 * @return string|null URL of the image or null if not found
 */
function getReleaseCoverArt($artist, $album) {
    // 1. MusicBrainz API Search URL
    // We search for the release-group to get the most general ID for that album
    $searchUrl = "https://musicbrainz.org/ws/2/release-group/?query=" . 
                 urlencode("artist:\"$artist\" AND releasegroup:\"$album\"") . 
                 "&fmt=json";

    $options = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: MyCoverArtScript/1.0 ( your-email@example.com )\r\n"
        ]
    ];

    $context = stream_context_create($options);
    
    try {
        // Step 1: Get the MBID from MusicBrainz
        $searchResponse = file_get_contents($searchUrl, false, $context);
        if (!$searchResponse) return null;

        $searchData = json_decode($searchResponse, true);
        
        if (empty($searchData['release-groups'])) {
            return "No album found for this artist.";
        }

        // Get the MBID of the first result
        $mbid = $searchData['release-groups'][0]['id'];

        // Step 2: Query the Cover Art Archive API
        // Format: https://coverartarchive.org/release-group/{mbid}
        $caaUrl = "https://coverartarchive.org/release-group/" . $mbid;

        // The CAA API returns a JSON list of images
        $caaResponse = @file_get_contents($caaUrl, false, $context);
        
        if (!$caaResponse) {
            return "No cover art available in the archive for this MBID.";
        }

        $caaData = json_decode($caaResponse, true);

        // Find the image marked as 'Front'
        foreach ($caaData['images'] as $image) {
            if ($image['front'] === true) {
                return $image['image']; // This is the direct link to the image
            }
        }

    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }

    return null;
}