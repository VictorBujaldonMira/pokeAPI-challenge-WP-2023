# PokéAPI-challenge-WP-2023

## Setting up the development environment

1- First of all clone the repository \
2- I used php 8.2, mariaBD and apache2. Just a LAMP server executed with Docker \
3- Write your wp-config.php, just a normal wp-config, nothing special \
4- Install the Wordpress or just import a empty database \
5- There are some typescript file se we have to compile before execute the app in /wp-content/plugins/pokemon-manager/assets/js \
6- There are some scss files so we have to compile also and minimize it with webpack in /wp-content/plugins/pokemon-manager/assets/css \
7- All config files are uploaded in case to reproduce the exact enviroment \
8- After setting all of that go to permalinks and save it as post-name, and also to regenerate all the links

## How to use the app
1- You can write some posts on the Pokémon CPT \
2- And you can see the Pokémon on domain.com/pokemon/(name of the pokemon) \
3- There is a shortcode that shows a filter and the Pokémons with pagination called ([pokemon_grid]). In this point I have to add that I didn't understand very well the point and only call the first 5 types for the filter, I don't know if thats the correct solution. \
4- There is an url domain.com/random, that shows some random Pokémon \
5- There is another url that generate a random Pokémon domain.com/generate\
6- And finally there are 2 url that shows some data in json mode: domain.com/wp-json/pokemon/details/(id_post) or domain.com/wp-json/pokemon/list

## Documentation of the development process
### How the solution was reached
First of all I made a roadmap to settle all the files I need how attack the problems and how many time I need to program all of it. I used old code and investigate any other, like typescript.
### Difficulties encountered
The first difficult I find was Typescript normally I didn't write a lot of code in Typescript, but I enjoyed to learn more and more about it. \
Second dificult is in every program there is something that doesn't work because you don't know why, so there are many of these but everything is solved. \
And finally I was testing everything before send and then on the change hour every post that I generate was on schedule mode so I was scared about something broke, but is only because the hour change. Now everything works fine.