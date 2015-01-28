<div class="task-group">
                    <div class="control-label">Task <?php echo $n?>:</div><br />
                    <div class="controls">Title: <input type='text' name='taskform[<?php echo $n; ?>][title]' /></div><br />
                    <div class="controls">Task Category: <select name="taskform[<?php echo $n; ?>][category]">
                            
                    <?php 
                    foreach($this->categories as $catg)
                    {
                        echo "<option value='$catg->id'>$catg->title</option>\n";
                    }
                     ?>
                        </select></div><br />
                    <div class="controls">Description: <textarea name='taskform[<?php echo $n; ?>][description]' wrap="off" cols="90" rows="4" style='overflow: auto;'></textarea>
                        <br /><br />Choose how you will measure the success of this task<br /><br />
                        <div style='float: left;'><select name='taskform[<?php echo $n; ?>][measure]'>
                            <option value='1'>Likes</option>
                            <option value='2'>Comments</option>
                            <option value='3'>Commitments</option>
                        </select></div>
                            <div style="float: right;">How Many? <input type='text' name="taskform[<?php echo $n; ?>][howmanylikes]" /></div>
                        <br /><br />
                        
                        <div class="control-group"><div class="control-label control-group">Skills Required:
                                <?php $r = 0; foreach ($tskill as $tskl) { ?><input type='hidden' value='<?php echo $r; ?>' id='skiinp_<?php echo $r; ?>' name="taskform[<?php echo $r; ?>][SkillInput][]" />
                                <ul class="token-input-list"><?php } ?>
             <?php $r = 0; foreach ($tskill as $tskl) { ?>
 <li class="token-input-token"><p>{{chosenSK.skill}}</p> <span class="token-input-delete-token" ng-click='deleteSKill(<?php echo $addTask;?> + task.id, chosenSK.id)'>×</span></li>
             <?php } ?>
     <li class="token-input-input-token">
 <input type='text' id='skillInput[<?php echo $n; ?>]' class="SkillInput" style='width: 100%' />
                
  
     </li></ul><div style='position: relative; margin-left: 50px;'><ul class='resultsList' id='resultsList{{<?php echo $addTask;?> + task.id}}'><li ng-click='chooseSkill(<?php echo $addTask;?> + task.id, skill.id, skill.skill)' ng-repeat='skill in skillResults[<?php echo $n; ?>]'>{{skill.skill}}</li></ul></div>
               </div></div>
                       
		</div><br /><br />
        </div>