<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/print.css"); ?>">
</head>
<body>
    <div class="uk-panel uk-panel-header">
        <h3 style="text-align:center;"><?php echo $this->session->userdata("profilKlinik") !== FALSE ? $this->session->userdata("profilKlinik") : "Klinik"; ?></h3>
        <h3 class="uk-panel-title" style="text-align:center;">Laba Rugi Periode <?php echo date("F",strtotime($this->input->post("opt_bulan"))); ?></h3>
        <table class="uk-table">
            <thead>
                <tr>
                    <th>No Akun</th>
                    <th>Nama Akun</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($qry_ja as $row_ja)
                {
                    $ja_id = $row_ja->ja_id;


                    $sql_ka = "SELECT * FROM kode_akun WHERE ka_jenis_akun=".$ja_id;
                    $qry_ka = $this->db->query($sql_ka);


                    ?>
                    <tr>
                        <td></td>
                        <td><span class="uk-text-bold"><?php echo strtoupper($row_ja->ja_ket); ?></span></td>
                        <td></td>
                    </tr>
                    <?php
                    $qty_biaya = 0;
                    foreach ($qry_ka->result() as $row_ka)
                    {

                        $sql_total = "SELECT SUM(td_qty*td_harga) AS harga FROM v_transaksi WHERE MONTH(th_insert_date) = ".$this->db->escape($bulan)." AND th_kode_akuntansi=".$row_ka->ka_id." GROUP BY MONTH(th_insert_date)";
                        $sql_total .= " UNION ";
                        $sql_total .= "SELECT SUM(pengeluaran_qty*pengeluaran_harga) as harga FROM v_pengeluaran WHERE MONTH(pengeluaran_insert_date) = ".$this->db->escape($bulan)." AND pengeluaran_kode_akun=".$row_ka->ka_id." GROUP BY MONTH(pengeluaran_insert_date)";
                        $sql_total .= " UNION ";
                        $sql_total .= "SELECT SUM(staff_gaji) as harga FROM v_payroll WHERE MONTH(payroll_insert_date) = ".$this->db->escape($bulan)." AND ka_kode='".$row_ka->ka_kode."' GROUP BY MONTH(payroll_insert_date)";
                        // echo $sql_total;
                        $qry_total = $this->db->query($sql_total);
                        $row_total = $qry_total->num_rows() > 0 ? $qry_total->row() : FALSE;
                        $total_harga = $row_total !== FALSE ? $row_total->harga : 0;

                        // echo substr($row_ka->ka_kode,0,1);
                        if (substr($row_ka->ka_kode,0,1) === 'P')
                        {
                            if ($qty_biaya === 0)
                            {
                                $qty_biaya = $total_harga;
                            }
                            else
                            {
                                $qty_biaya = $qty_biaya-$total_harga;
                            }
                        }
                        else
                        {
                            $qty_biaya = $qty_biaya+$total_harga;
                        }

                        ?>
                        <tr>
                            <td><?php echo $row_ka->ka_kode; ?></td>
                            <td><?php echo ucwords(strtolower($row_ka->ka_akun)); ?></td>
                            <td><span class="uk-align-right"><?php echo number_format($total_harga,0,',','.'); ?></span></td>
                        </tr>
                        <?php
                    }
                    if ($total === 0)
                    {
                        $total = $qty_biaya;
                    }
                    else
                    {
                        $total = $total - $qty_biaya;
                    }
                    ?>
                    <tr>
                        <td></td>
                        <td><span class="uk-text-bold">Jumlah</span></td>
                        <td><span class="uk-text-bold uk-align-right"><?php echo number_format($qty_biaya,0,',','.'); ?></span></td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td></td>
                    <td><span class="uk-text-danger uk-text-bold">TOTAL (Pendapatan - Biaya)</span></td>
                    <td><span class="uk-align-right uk-text-bold uk-text-danger"><?php echo number_format($total,0,',','.'); ?></span></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
