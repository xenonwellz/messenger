##  Messenger Laravel Package

#### A Laravel package helps you add a real-time messaging system to your application, it supports both pusher and laravel websockets.


### Features
- Configure who can chat with who
- Real time 
- Users chat system.
- Real-time user's active status.
- Real-time typing indicator.
- Real-time seen messages indicator.
- Responsive design with all devices.
- Upload attachments (Allows you to customize allowed files to be uploaded).

## Announcement

***I will not be updating this package till the next two weeks because i currently have exams but plans for next version is already in place. You expect something more special in the next release***

## Installation 

### Prerequisite
  - ***Composer***
  - ***Laravel >= 5.4***
  - ***PHP >= 5.3.2***

 Xenonwellz Messenger can be installed with composer using the following command

` composer require xenonwellz/messenger `

Publish the assets by running 

`php artisan vendor:publish --tag=messenger-assets`

 You can publish the config by entering the following command

 `php artisan vendor:publish --tag=messenger-config`

 After runing the 
 `php artisan migrate`
 command, uncomment the
 `allow_conversation_with`
 field in the config.


## Customization

### Config keys

`allow_conversation_with`
field allows you to specify specific users that can message each other it must return a query (collections will throw an error). Uncomment after first migrate command

`use_avatar_field`
 This should return either true or false if set to true it will use a column frrom the database as the path to the image that will serve as profile picture for each user.

`avatar_field_name` Returns the name of the column that will be used for a specific user's profile picture. to use this, you must set 
`use_avatar_field` to true.

 `default_avatar` This field allows you to specify either a full url or a link to an image on the server that could be accessed with Storage::get() if set to empty the users avatar will automatically be st using the uiavatars api.

`allowed_mime_types` allows you to specify the files that users can send to each other seperated with commas.

`max_file_size` allows you too set the size of each file that users can send to each other.

`max_file_at_once` allows you to set the amount of files users can send at once.

`websocket_provider` Allows you to set your websocket provider. "pusher" to use Pusher's api or "laravel-websockets" to use laravel websockets api. (You should set up your backend how you normallly would for either pusher or Laravel Websockets. Support for Socket.io is coming soon).

`force_websocket_ssl/tls` If using laravel websockets you can configure either to force TLS or not. You will need to set up websocket tls on you server before doing this.

`route_prefix` Allows you to set the route for the messenger.

### Other customizations

You can always customize anything you want by publishing it first. You can publish them by anything by running this command 

`php artisan vendor:publish --tag=messenger-whatever` 

and change the tag based on what you want to publish. (i.e messenger-views to pubish views, messenger-migrations to publish migrations).

## Conclusion
You can always open issues if you find any bug or security issue. Thanks for using this package.

**This project was developed by Ovabor Obed (Xenonwellz) under the MIT License. I'm currently available for hire especially for remote jobs. Thanks for using this package. Don't forget to Star.**