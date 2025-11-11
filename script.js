  function getDato () {
    let reserva = document.forms("Reserva");

    let nombre = reserva.nombre.value;
    let fechaE = document.fechaE.value;
    let fechaS = reserva.fechaS.value;
    let adultos = reserva.adultos.value;
    let ninos = reserva.ninos.value;
    let hab = reserva.hab.value;
    let email = reserva.email.value;

   console.log(reserva);
}

function ubi(){
  var coor = {lat:-84.1136441 ,lng:-84.1110692};
  var mapa = new google.maps.Map(document.getElementById("buscar"),{ 
    zoom: 5,
    center: coor
  });
var marker = new google.maps.Marker({
  position: coor,
  mapa: mapa
})

}
