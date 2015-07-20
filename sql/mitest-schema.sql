BEGIN;

-- categoria
CREATE TABLE categoria (
    id integer NOT NULL,
    categoria character varying(50) NOT NULL,
    usuario integer NOT NULL,
    madre integer,
    publica boolean DEFAULT true NOT NULL,
    orden smallint DEFAULT 0 NOT NULL
);
COMMENT ON TABLE categoria IS 'Tabla para categorías de las pruebas';
COMMENT ON COLUMN categoria.id IS 'Identificador de la categoría';
COMMENT ON COLUMN categoria.categoria IS 'Nombre de la categoría';
COMMENT ON COLUMN categoria.usuario IS 'Dueño de la categoría';
COMMENT ON COLUMN categoria.madre IS 'Categoría madre de esta categoría';
COMMENT ON COLUMN categoria.publica IS 'Indica si es visible para todos o solo para su dueño';
COMMENT ON COLUMN categoria.orden IS 'Orden en que debe ser listada';
CREATE SEQUENCE categoria_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

-- pregunta
CREATE TABLE pregunta (
    id integer NOT NULL,
    pregunta text NOT NULL,
    prueba integer NOT NULL,
    tipo integer NOT NULL,
    imagen_name character varying(50),
    imagen_type character varying(10),
    imagen_size integer,
    imagen_data bytea,
    explicacion text,
    publica boolean DEFAULT true NOT NULL,
    activa boolean DEFAULT true NOT NULL
);
COMMENT ON TABLE pregunta IS 'Tabla para las preguntas de las pruebas';
COMMENT ON COLUMN pregunta.id IS 'Identificador de la pregunta';
COMMENT ON COLUMN pregunta.pregunta IS 'Pregunta';
COMMENT ON COLUMN pregunta.prueba IS 'Prueba a la que pertenece la pregunta';
COMMENT ON COLUMN pregunta.tipo IS 'Tipo de pregunta (por ejemplo indicará si es fácil, normal o difícil)';
COMMENT ON COLUMN pregunta.imagen_name IS 'Nombre del archivo de la imagen';
COMMENT ON COLUMN pregunta.imagen_type IS 'Mimetype de la imagen';
COMMENT ON COLUMN pregunta.imagen_size IS 'Tamaño de la imagen';
COMMENT ON COLUMN pregunta.imagen_data IS 'Imagen';
COMMENT ON COLUMN pregunta.explicacion IS 'El porque la o las respuestas correctas son las correctas';
COMMENT ON COLUMN pregunta.publica IS 'Indica si es visible para todos o solo para su dueño';
CREATE SEQUENCE pregunta_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

-- prueba
CREATE TABLE prueba (
    id integer NOT NULL,
    prueba character varying(100) NOT NULL,
    descripcion text,
    categoria integer NOT NULL,
    creada timestamp without time zone DEFAULT now() NOT NULL,
    modificada timestamp without time zone DEFAULT now() NOT NULL,
    publica boolean DEFAULT true NOT NULL,
    orden smallint DEFAULT 0 NOT NULL
);
COMMENT ON TABLE prueba IS 'Tabla para pruebas de los usuarios';
COMMENT ON COLUMN prueba.id IS 'Identificador de la prueba';
COMMENT ON COLUMN prueba.prueba IS 'Título o nombre de la prueba';
COMMENT ON COLUMN prueba.descripcion IS 'Descripción de la prueba';
COMMENT ON COLUMN prueba.categoria IS 'Categoría a la que pertenece la prueba';
COMMENT ON COLUMN prueba.creada IS 'Cuando fue creada';
COMMENT ON COLUMN prueba.modificada IS 'Cuando fue modificada por última vez';
COMMENT ON COLUMN prueba.publica IS 'Indica si es visible para todos o solo para su dueño';
COMMENT ON COLUMN prueba.orden IS 'Orden en que debe ser listada';
CREATE SEQUENCE prueba_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

-- respuesta
CREATE TABLE respuesta (
    id integer NOT NULL,
    respuesta text NOT NULL,
    pregunta integer NOT NULL,
    correcta boolean DEFAULT false NOT NULL
);
COMMENT ON TABLE respuesta IS 'Tabla para respuestas de las preguntas';
COMMENT ON COLUMN respuesta.id IS 'Identificador de la respuesta';
COMMENT ON COLUMN respuesta.respuesta IS 'Posible respuesta a la pregunta';
COMMENT ON COLUMN respuesta.pregunta IS 'Pregunta a la que pertenece la respuesta';
COMMENT ON COLUMN respuesta.correcta IS 'Indica si la respuesta es correcta';
CREATE SEQUENCE respuesta_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

-- tipo
CREATE TABLE tipo (
    id integer NOT NULL,
    tipo character varying(10) NOT NULL,
    peso smallint DEFAULT 0 NOT NULL,
    porcentaje smallint DEFAULT 0 NOT NULL
);
COMMENT ON TABLE tipo IS 'Tabla para los tipos de preguntas que pueden existir';
COMMENT ON COLUMN tipo.id IS 'Identificador del tipo';
COMMENT ON COLUMN tipo.tipo IS 'Nombre del tipo (ej: fácil, normal o difícil)';
COMMENT ON COLUMN tipo.peso IS 'Indica dificultad (menor número, más fácil la pregunta)';
COMMENT ON COLUMN tipo.porcentaje IS 'Porcentaje por defecto utilizado para seleccionar preguntas cuando se hace al azar';
CREATE SEQUENCE tipo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

-- secuecias
ALTER TABLE ONLY categoria ALTER COLUMN id SET DEFAULT nextval('categoria_id_seq'::regclass);
ALTER TABLE ONLY pregunta ALTER COLUMN id SET DEFAULT nextval('pregunta_id_seq'::regclass);
ALTER TABLE ONLY prueba ALTER COLUMN id SET DEFAULT nextval('prueba_id_seq'::regclass);
ALTER TABLE ONLY respuesta ALTER COLUMN id SET DEFAULT nextval('respuesta_id_seq'::regclass);
ALTER TABLE ONLY tipo ALTER COLUMN id SET DEFAULT nextval('tipo_id_seq'::regclass);

-- llaves primarias
ALTER TABLE ONLY categoria
    ADD CONSTRAINT categoria_pkey PRIMARY KEY (id);
ALTER TABLE ONLY pregunta
    ADD CONSTRAINT pregunta_pkey PRIMARY KEY (id);
ALTER TABLE ONLY prueba
    ADD CONSTRAINT prueba_pkey PRIMARY KEY (id);
ALTER TABLE ONLY respuesta
    ADD CONSTRAINT respuesta_pkey PRIMARY KEY (id);
ALTER TABLE ONLY tipo
    ADD CONSTRAINT tipo_pkey PRIMARY KEY (id);

-- índices
CREATE INDEX categoria_madre_idx ON categoria USING btree (madre);
CREATE INDEX categoria_madre_publica_idx ON categoria USING btree (madre, publica);
CREATE INDEX categoria_publica_idx ON categoria USING btree (publica);
CREATE INDEX pregunta_prueba_idx ON pregunta USING btree (prueba);
CREATE INDEX pregunta_prueba_publica_idx ON pregunta USING btree (prueba, publica);
CREATE INDEX pregunta_prueba_tipo_idx ON pregunta USING btree (prueba, tipo);
CREATE INDEX pregunta_prueba_tipo_publica_idx ON pregunta USING btree (prueba, tipo, publica);
CREATE INDEX prueba_categoria_idx ON prueba USING btree (categoria);
CREATE INDEX prueba_categoria_publica_idx ON prueba USING btree (categoria, publica);
CREATE INDEX prueba_publica_idx ON prueba USING btree (publica);
CREATE INDEX respuesta_pregunta_correcta_idx ON respuesta USING btree (pregunta, correcta);
CREATE INDEX respuesta_pregunta_idx ON respuesta USING btree (pregunta);

-- llaves foráneas
ALTER TABLE ONLY categoria
    ADD CONSTRAINT categoria_madre_fkey FOREIGN KEY (madre) REFERENCES categoria(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY categoria
    ADD CONSTRAINT categoria_usuario_fkey FOREIGN KEY (usuario) REFERENCES usuario(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY pregunta
    ADD CONSTRAINT pregunta_prueba_fkey FOREIGN KEY (prueba) REFERENCES prueba(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ONLY pregunta
    ADD CONSTRAINT pregunta_tipo_fkey FOREIGN KEY (tipo) REFERENCES tipo(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY prueba
    ADD CONSTRAINT prueba_categoria_fkey FOREIGN KEY (categoria) REFERENCES categoria(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY respuesta
    ADD CONSTRAINT respuesta_pregunta_fkey FOREIGN KEY (pregunta) REFERENCES pregunta(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;
    
COMMIT;
