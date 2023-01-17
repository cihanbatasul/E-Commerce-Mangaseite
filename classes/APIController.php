<?php
class APIController
{

    private $ch;

    public function __construct(private string $requestUrl)
    {
        $this->ch = curl_init();
    }


    public function requestData($title)
    {
        curl_setopt($this->ch, CURLOPT_URL, $this->requestUrl . "/manga?title=" . $title);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL session
        $response = curl_exec($this->ch);

        // Decode the JSON response
        $response = json_decode($response, true);


        return $response;
    }

    public function extractMangaIDs($response)
    {
        // Extract the manga IDs from the response
        $mangaIds = array_map(function ($manga) {
            $data = array();
            array_push($data,  $manga['id'], $manga['title']);
            return $data;
        }, $response['data']);

        return $mangaIds;
    }

    public function getMangaChapters($ID)
    {
        curl_setopt($this->ch, CURLOPT_URL, "{$this->requestUrl}/manga/{$ID}/feed");
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL session
        $response = curl_exec($this->ch);

        // Decode the JSON response
        $response = json_decode($response, true);


        return $response;
    }

    public function __destruct()
    {
        // Close cURL session
        curl_close($this->ch);
    }
}
