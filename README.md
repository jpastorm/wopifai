# WOPIFAI

### Api rest de musica
Wopifai es una api rest de musica diseñada para poder escanear una carpeta dentro de tu sistema operativo reconociendo los archivos mp3 del mismo, de esta forma se guardara automaticamente dentro de la base de datos y asi poder usar el cover y el track de la cancion que se podra stremear desde un navegador o dispositivo.
# Endpoints del explorador
  - /api/dir/
  - /api/dir/tag
  - /api/dir/cover
  - /api/dir/stream
  - /api/dir/scan

# Endpoints de la libraria
  - /api/library/
  - /api/library/addlibrary
  - /api/library/getAll/{id}
# Endpoints de la Cancion
  - /api/cancion/{hash}
  - /api/cancion/{hash}/cover
  - /api/cancion/{hash}/meta
  - /api/cancion/{hash}/streamtrack
