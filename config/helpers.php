<?php

if (!function_exists('put_request')) {
    /**
     * @param string
     * @return mixed
     * @see https://stackoverflow.com/questions/9464935/php-multipart-form-data-put-request
     */
    function put_request(string $parameter = '')
    {
        // Fetch content and determine boundary
        $raw_data = file_get_contents('php://input');
        $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));

        // Return an empty array if no field was sent
        if ($raw_data === '' && $boundary === '') {
            return [];
        }

        // Fetch each part
        $parts = array_slice(explode($boundary, $raw_data), 1);
        $data = array();

        foreach ($parts as $part) {
            // If this is the last part, break
            if ($part == "--\r\n") break;

            // Separate content from headers
            $part = ltrim($part, "\r\n");
            list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

            // Parse the headers list
            $raw_headers = explode("\r\n", $raw_headers);
            $headers = array();
            foreach ($raw_headers as $header) {
                list($name, $value) = explode(':', $header);
                $headers[strtolower($name)] = ltrim($value, ' ');
            }

            // Parse the Content-Disposition to get the field name, etc.
            if (isset($headers['content-disposition'])) {
                preg_match(
                    '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/',
                    $headers['content-disposition'],
                    $matches
                );
                list(, $type, $name) = $matches;

                // handle your fields here
                $data[$name] = substr($body, 0, strlen($body) - 2);
            }
        }

        if ($parameter !== '') {
            return $data[$parameter] ?? '';
        }
        // ...
        return $data;
    }
}