

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">


            <div class="panel panel-default">
                <div class="panel-heading">Instructies</div>

                <div class="panel-content">

                <h1>Algemeen</h1>
                <ul>
                    <li>Maak deze opdracht zo goed mogelijk na, het idee is gebaseerd op <a href="http://news.ycombinator.com">Hackernews</a> of <a href="http://www.reddit.com">reddit</a>.</li>
                    <li>Probeer zoveel mogelijk functionaliteit uit, maak een grondige analyse van wat er allemaal in deze applicatie verwerkt zit. Daarbij kan pen en papier helpen om notities te maken en een overzicht te verkrijgen.</li>
                    <li>De oplossing moet op je Github repository én online komen te staan. Ik moet deze dus kunnen clonen én ik moet naar deze applicatie kunnen surfen</li>
                    <li>De layout is ondergeschikt en mag je integraal overnemen (= bootstrap)</li>
                    <li>Je mag NERGENS JavaScript gebruiken. Alle errorafhandeling gebeurt dus via PHP.</li>
                    <li>Je mag kiezen of je dit vanaf scratch, CodeIgniter of Laravel maakt. De versie die je hier zit is gemaakt in Laravel en ik zou dit ten zeerste aanraden om dit ook te proberen, maar jullie zijn vrij om hierin te beslissen.</li>
                    <li>Jullie schrijven alles zelf en kunnen elke lijn code die jullie hebben toegevoegd uitleggen (met uitzondering van de framework internals, mits ze niet al te relevant zijn voor de oplossing)</li>
                    <li>Alle coding ethics en basisprincipes van security, ... die tijdens de lessen en de oefeningen gezien zijn, moeten ook hier nauwkeurig worden toegepast</li>
                    <li>De applicatie wordt elke dag om 03u00 gerest naar de oorspronkelijke database-waarden. Jullie mogen dus zoveel testen als jullie willen, maar hou er rekening mee dat jullie elke dag opnieuw zullen moeten registreren.</li>
                    <li>Let goed op welke delen er publiek toegankelijk zijn, voor welke delen je moet inloggen en over welke functionaliteit je beschikt als je bent ingelogd</li>
                    <li>Er zijn veel functioanliteiten die je zou kunnen verbeteren of toevoegen, maar hou je aan de opdracht, deze is al pittig genoeg. Ben je klaar en werkt alles zoals het hoort, dan kan je nog altijd dingen toevoegen, maar werken deze niet, dan is dat nefast voor je beoordeling</li>
                    <li>Gebruik git! Ik wil een mooie commit history zien, en liefst ook branches per functionaliteit die je hebt toegevoegd.</li>
                </ul>

                <h2>Database</h2>

                <ul>
                    <li>Denk zelf na welke databasestructuur hier geschikt zou zijn</li>
                    <li>Er worden GEEN hard deletes uitgevoerd.</li>
                    <li>Als je opdracht is afgewerkt doe je een export van je database (inclusief databasenaam en structuur) en plaats je deze in de root van je project.</li>
                </ul>
            
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>