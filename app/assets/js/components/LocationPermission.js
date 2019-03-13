import React from 'react';

const LocationPermission = () => {
  return (
    <div className=''>
      <h2>Orange a besoin de connaître le pays dans lequel vous êtes pour vous permettre de :</h2>
      <ul className='pl-0 ml-3'>
        <li>Suivre votre consommation où vous êtes</li>
        <li>Fluidifier vos achats de recharges</li>
        <li>Débloquer votre forfait data si besoin</li>
      </ul>
      <p className='mt-4'>En cas de refus, vous pourrez entrer manuellement la localisation.</p>
      <div className='mt-5'>Autorisez-vous Orange à localiser le pays dans lequel vous êtes ?</div>
      <div className='mt-5 mb-3'>
        <a className="col-12 col-lg-5 mr-2 mb-2 mb-lg-0 btn btn-primary" href="#" role="button">
          Oui, j'autorise la localisation
        </a>
        <a className="col-12 col-lg-5 btn btn-secondary" href="#" role="button">Non, je refuse la localisation</a>
      </div>
    </div>
  );
};

export default LocationPermission;
