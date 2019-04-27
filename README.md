# hirecarAPI
API set for the hirecar project : https://github.com/nathsou/hirecar
-
# 1) get_parking_lots : requete de parking  

##paramètre obligatoire:

a) center_lat , center_lng, radius

##paramétre de option pour resserer la rechercher:

a)min_price

b)max_price

c)start_date

d)end_date

e)number_places

#2) get_car: requete de voiture

##paramètre obligatoire soit l'un soit l'autre:

a) center_lat , center_lng, radius
b) airportId

##paramétre de option pour resserer la rechercher:

a) min_price

b) max_price

c) start_date

d) end_date

e) gearBox

f) nbPlaces

g) nbPorte

h) fuel

i) model

SELECT DISTINCT `parking_lot`.`id`, `parking_lot`.`label` ,`parking_lot`.`lat`, `parking_lot`.`lng`, `parking_lot`.`nb_places`, `parking_lot`.`price_per_day`, `parking_lot`.`airport_id`, countTable.countPlaceTaken FROM `parking_lot`, (SELECT (count(parking_lot_id))as countPlaceTaken,parking_lot_id FROM rent_parking_spot) as countTable WHERE parking_lot.id = countTable.parking_lot_id AND (SQRT(POW((`parking_lot`.`lat`-7),2)+POW((`parking_lot`.`lng`-42),2))) < 30