package Repository;

import Domain.Usuario;

public interface UsuarioRepository extends JpaRepository<Usuario, Long>{
    public Usuario findByUsername(String username);
     public Usuario findByUsernameAndPassword(String username, String Password);

    public Usuario findByUsernameOrCorreo(String username, String correo);
    public boolean existsByUsernameOrCorreo(String username, String correo);
}
