package Service;

import Domain.*;
import Repository.RolRepository;
import Repository.UsuarioRepository;
import java.util.List;

public class UsuarioService {
    @Autowired
    private UsuarioRepository usuarioRepository;

    @Autowired
    private RolRepository rolRepository;

    @Transactional(readOnly = true)
    public List<Usuario> getUsuarios() {
        return usuarioRepository.findAll();
    }

    @Transactional(readOnly = true)
    public Usuario getUsuario(Usuario usuario) {
        return usuarioRepository.findById(usuario.getIdUsuario()).orElse(null);
    }

    @Transactional(readOnly = true)
    public Usuario getUsuarioPorUsername(String username) {
        return usuarioRepository.findByUsername(username);
    }

    @Transactional(readOnly = true)
    public Usuario getUsuarioPorUsernameYPassword(String username, String password) {
        return usuarioRepository.findByUsernameAndPassword(username, password);
    }

    @Transactional(readOnly = true)
    public Usuario getUsuarioPorUsernameOCorreo(String username, String correo) {
        return usuarioRepository.findByUsernameOrCorreo(username, correo);
    }

    @Transactional(readOnly = true)
    public boolean existeUsuarioPorUsernameOCorreo(String username, String correo) {
        return usuarioRepository.existsByUsernameOrCorreo(username, correo);
    }

    @Transactional
    public void save(Usuario usuario, boolean crearRolUser){
        usuario = usuarioRepository.save(usuario);
        if (crearRolUser) {
            Rol rol = new Rol();
            rol.setNombre("ROLE_USER");
            rol.setIdUsuario(usuario.getIdUsuario());
            rolRepository.save(rol);
        }
    }

    @Transactional
    public boolean delete(Usuario usuario){
        try {
            usuarioRepository.delete(usuario);
            usuarioRepository.flush();
            return true;
        } catch (Exception e) {
            return false;
        }
    }



}
