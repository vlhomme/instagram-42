docker container rm -f $(docker container ls -aq);
docker image rm -f $(docker image ls -aq);
echo 'path ?';
read path;
$path .= "/*";
rm -rf ~/Desktop/New
cp -r "$path" ~/Desktop/New
docker-compose -f ~/Desktop/New/www/camagru/DOCKER/docker-compose.yml up -d
