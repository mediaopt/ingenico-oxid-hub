# Ingenico OXID

## Dev

Image bauen: docker build -t ingenico .
Container starten: docker run --name ingenico1 -v $PWD/shops:/var/www/html/ -t -p 8087:80 ingenico

## ToDo
- functions php erweiterung über event wenn möglich
- fehler functions.php abfangen
- generell besser abfangen
- modul description zu dünne
- datei header
- db setup nicht über, interface
- config in modulreiter
- log-ausgabe? so recht schlecht, besser aufbereiten?
- bestellungen markieren? bei fehlern?
- verbindungscheck?
- zahlungsarten aktiv bei installation?
- Fehlerhandling, was tun bei sha-mismatch?
- Feedback Betrugserkennung verarbeiten
- doku im wiki checken, ob aktuell
- order error page auf bestehende seite integrieren?!
- im backend besser sichtbar machen ob live oder testmodus aktiv
- weiterleitung nach amount mismatch auf paymentseite wirklich sinnvoll?
- fehlermeldung scheint nicht abgeräumt zu werden