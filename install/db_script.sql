/*
 * Copyright (C) 2014 Jairo Rojas Delgado [jrdelgado@estudiantes.uci.cu].
 * 
 * This file is part of "La Coletilla v.1.0.".
 *
 * This software is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public Licence as published by
 * the Free Software Foundation, either versión 3 of the Licence, or
 * any later version.
 *
 * This software is distributed in the hope tha it will be useful,
 * but WITHOUT ANY WARRANY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public Licence for more details.
 *
 * You should have received a copy of the GNU General Public Licence
 * along with the software. If not, see <http://www.gnu.org/licenses/>.
 */
 
CREATE TABLE tb_question
(
  id serial NOT NULL,
  content character varying,
  published boolean,
  post_date timestamp without time zone,
  
  CONSTRAINT pk_question PRIMARY KEY (id )
) 
WITH (OIDS=FALSE);

ALTER TABLE tb_question OWNER TO postgres;

CREATE TABLE tb_user
(
  id serial NOT NULL,
  nick_name character varying,
  secret character varying,
  email character varying,
  administrator boolean,
  
  CONSTRAINT pk_user PRIMARY KEY (id ),
  CONSTRAINT un_user UNIQUE (nick_name )
)
WITH ( OIDS=FALSE);

ALTER TABLE tb_user OWNER TO postgres;


CREATE TABLE tb_answer
(
  id serial NOT NULL,
  content character varying,
  published boolean,
  post_date timestamp without time zone,
  question integer,
  author integer,
  
  CONSTRAINT pk_answer PRIMARY KEY (id ),
  CONSTRAINT fk_question FOREIGN KEY (question) REFERENCES tb_question (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fk_user FOREIGN KEY (author)       REFERENCES tb_user (id)     MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (OIDS=FALSE);

ALTER TABLE tb_answer OWNER TO postgres;
