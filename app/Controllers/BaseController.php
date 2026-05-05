<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];
    protected $data = [];
    var $nama = null;
    var $daftarKegiatan = null;

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //  memnggunakan base_url
        helper(['url', 'auth']); // Memuat helper URL

        // Preload any models, libraries, etc, here.

        // inisiasi model

        // E.g.: $this->session = service('session');
        $this->nama = 'Syafiq';
        $daftarKegiatan = null;
        // fallback untuk kegiatan aktif kalau sessionya masih ada.
        if (auth()->loggedIn() && !session()->has('aktif_kegiatan')) {
            $wilayahTugasModel = new \App\Models\WilayahTugasModel();
            $kegiatanModel = new \App\Models\KegiatanModel();
            $terbaru = null;            // cek role user. apakah mitra
            $daftarKegiatan = null;
            if (auth()->getGroups()[0] == 'mitra') {
                $daftarKegiatan = $wilayahTugasModel->getKegiatanByUser(auth()->id());
                $terbaru = $daftarKegiatan[0];
            } else {
                $daftarKegiatan = $kegiatanModel->getKegiatanDesc();
                $terbaru = $daftarKegiatan[0];
            }

            if ($daftarKegiatan) {
                // Simpan ke session otomatis setelah login
                session()->set('aktif_kegiatan', $terbaru['id']);
                session()->set('nama_kegiatan', $terbaru['nama_kegiatan']);
                session()->set('is_rt', $terbaru['is_rt']);
                session()->set('level_wilayah', $terbaru['level_wilayah']);
            }
        }
    }
}
