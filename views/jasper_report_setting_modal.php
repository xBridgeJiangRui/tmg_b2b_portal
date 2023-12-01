    <div id="edit_report" data-backdrop="static" data-keyboard="false" class="modal fade">
         <div class="modal-dialog">
              <form method="post" action="<?php echo base_url(); ?>index.php/Report_jasper_controller/addreport" id="user_form2">
                   <div class="modal-content">
                        <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal">&times;</button>
                             <h4 class="modal-title">Edit Report</h4><h4 class="modal-title" id="report_tittle"></h4>
                        </div>
                        <div class="modal-body">
                        <div class="form-group">
                          <input type="hidden" name="ori_web_index" id="ori_web_index" />
                          <input type="hidden" name="childID" id="childID" />
                          <label>Parent Report</label>
                          <select id="parentreport" name="parentreport" class="form-control select2" style="width: 100%;">
                         <option value="0">No Parent Report</option>

                          <?php foreach($menu as $row)
                          {
                          ?>
                          <option value="<?php echo $row->childID;?>"><?php echo $row->Description;?></option>
                          <?php
                          }
                          ?>

                          
                          </select>
                        </div>

                             <label>seq</label>
                             <input type="number" name="seq" autocomplete="off" spellcheck="false" placeholder="seq" id="seq" class="form-control"  autofocus required/>
                             <input type="hidden" name="seqoriginal" id="seqoriginal" />
                             <span id="result_reportmenuseq"></span>
                             <br />
                             <label>Report Description</label>
                             <input type="text" name="descriptionss" spellcheck="false" id="descriptionss" class="form-control" placeholder="REPORT DESCRIPTION" autocomplete="off" required/>
                             <br />
                             <label>Jasper Report Url</label>
                             <textarea class="form-control" id="jasper_report_url" name="jasper_report_url" rows="3" placeholder="Jasper Report Url ..."></textarea>
                             <span id="result_reportmenuseq"></span>
                             <br />
                             <label>Jasper Report Folder</label>
                             <textarea class="form-control" id="jasper_report_folder" name="jasper_report_folder" rows="3" placeholder="Jasper Report Folder ..."></textarea>
                             <span id="result_reportmenuseq"></span>
                             <br />
                             <label>Report Query Index</label>
                             <input type="text" name="web_index" spellcheck="false" placeholder="REPORT Query" id="web_index" class="form-control" autocomplete="off" required/>
                             <input type="hidden" name="webindexoriginal" id="webindexoriginal" />
                             <span id="result_reportmenu"></span>
                             <br />

                             <label >Hide</label>
                             <input type="checkbox" name="hidestatus" id="hidestatus" value="1"/>
                        </div>
                        <div class="modal-footer">
                             <input type="submit" name="actions1" id="actions1" class="btn btn-primary" value="" />
                             <input type="hidden" name="actions" id="actions" class="btn btn-primary" value="" />
                             <button type="button" id="close" onClick="window.location.reload();" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                   </div>
              </form>
         </div>
    </div>

<div class="modal fade" id="delete" role="dialog">
    <div class="modal-dialog">
      <form method="post" action="<?php echo base_url(); ?>index.php/Report_jasper_controller/deletereport">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="modal_detail" id="deletereportdescription" style="text-align: center">Are you sure want to remove this report <span class="grt"></span> ?</h4>
            </div>
            <div class="modal-footer" style="text-align: center">
            <span id="preloader-delete"></span>
            <input type="hidden" name="childID" id="reportchildID" value=""/>
                <a id="url" href=""><button type="submit" class="btn btn-sm btn-danger"><span class="fa fade-trash"></i> Delete</button></a>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
      </form>
    </div>
</div>

