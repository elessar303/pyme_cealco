DROP FUNCTION IF EXISTS `codigoPadre`$

CREATE FUNCTION `codigoPadre`(padre varchar(18)) RETURNS int(11)
BEGIN
return (select id_item from item where cod_item= padre);
END$
