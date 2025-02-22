<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service;

use Google\Client;

/**
 * Service definition for DisplayVideo (v4).
 *
 * <p>
 * Display & Video 360 API allows users to automate complex Display & Video 360
 * workflows, such as creating insertion orders and setting targeting options
 * for individual line items.</p>
 *
 * <p>
 * For more information about this service, see the API
 * <a href="https://developers.google.com/display-video/" target="_blank">Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class DisplayVideo extends \Google\Service
{
  /** Create, see, edit, and permanently delete your Display & Video 360 entities and reports. */
  const DISPLAY_VIDEO =
      "https://www.googleapis.com/auth/display-video";
  /** View and manage your reports in DoubleClick Bid Manager. */
  const DOUBLECLICKBIDMANAGER =
      "https://www.googleapis.com/auth/doubleclickbidmanager";

  public $media;
  public $sdfdownloadtasks_operations;
  public $sdfuploadtasks_operations;
  public $rootUrlTemplate;

  /**
   * Constructs the internal representation of the DisplayVideo service.
   *
   * @param Client|array $clientOrConfig The client used to deliver requests, or a
   *                                     config array to pass to a new Client instance.
   * @param string $rootUrl The root URL used for requests to the service.
   */
  public function __construct($clientOrConfig = [], $rootUrl = null)
  {
    parent::__construct($clientOrConfig);
    $this->rootUrl = $rootUrl ?: 'https://displayvideo.googleapis.com/';
    $this->rootUrlTemplate = $rootUrl ?: 'https://displayvideo.UNIVERSE_DOMAIN/';
    $this->servicePath = '';
    $this->batchPath = 'batch';
    $this->version = 'v4';
    $this->serviceName = 'displayvideo';

    $this->media = new DisplayVideo\Resource\Media(
        $this,
        $this->serviceName,
        'media',
        [
          'methods' => [
            'download' => [
              'path' => 'download/{+resourceName}',
              'httpMethod' => 'GET',
              'parameters' => [
                'resourceName' => [
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ],
              ],
            ],'upload' => [
              'path' => 'media/{+resourceName}',
              'httpMethod' => 'POST',
              'parameters' => [
                'resourceName' => [
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ],
              ],
            ],
          ]
        ]
    );
    $this->sdfdownloadtasks_operations = new DisplayVideo\Resource\SdfdownloadtasksOperations(
        $this,
        $this->serviceName,
        'operations',
        [
          'methods' => [
            'get' => [
              'path' => 'v4/{+name}',
              'httpMethod' => 'GET',
              'parameters' => [
                'name' => [
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ],
              ],
            ],
          ]
        ]
    );
    $this->sdfuploadtasks_operations = new DisplayVideo\Resource\SdfuploadtasksOperations(
        $this,
        $this->serviceName,
        'operations',
        [
          'methods' => [
            'get' => [
              'path' => 'v4/{+name}',
              'httpMethod' => 'GET',
              'parameters' => [
                'name' => [
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ],
              ],
            ],
          ]
        ]
    );
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DisplayVideo::class, 'Google_Service_DisplayVideo');
