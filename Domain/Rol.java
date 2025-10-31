package Domain;

import java.io.Serializable;
import java.util.List;

@Data
@Entity
@Table(name = "rol")
public class Rol implements Serializable {
    private static final long serialVersionUID = 1L;

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long idRol;
    private String nombre;
    private Long idUsuario;

}
