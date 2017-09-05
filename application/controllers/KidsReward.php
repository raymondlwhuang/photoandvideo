<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class KidsReward extends MY_Controller {
	public function index()
	{
		$this->load->model('kidsreward_model');
		if($this->input->post('Delete_x'))
		{	
			$this->general_model->_delete(array('table'=>'kidsreward','id'=>$this->input->post('searchID')));
		}
		ELSEIF($this->input->post('Previous_x'))
		{
			$result= $this->kidsreward_model->navigate(array('id'=>$this->input->post('searchID'),'navigate'=>'Previous'));
		}
		ELSEIF($this->input->post('Next_x'))
		{
			$result= $this->kidsreward_model->navigate(array('id'=>$this->input->post('searchID'),'navigate'=>'Next'));
		}
		ELSEIF($this->input->post('First_x'))
		{
			$result= $this->kidsreward_model->navigate(array('navigate'=>'First'));
		}
		ELSEIF($this->input->post('Last_x'))
		{
			$result= $this->kidsreward_model->navigate(array('navigate'=>'Last'));
		} 
		ELSEIF($this->input->post('Save_x'))
		{
			$searchID3 = $this->input->post('searchID');
			$description =$this->input->post('description');
			$rewardid = $this->input->post('rewardid');
			$amount = $this->input->post('amount');
			$signature = $this->input->post('signature');
			$this->general_model->_update(array('table'=>'kidsreward','id'=>$this->input->post('searchID')),array('description'=>$description,'rewardid'=>$rewardid,'amount'=>$amount,'signature'=>$signature));
			$result=$this->general_model->_get(array('table'=>'kidsreward','id'=>$this->input->post('searchID')));
		}
		ELSEIF($this->input->post('submit_x'))
		{
			$fmdate = $this->input->post('FmDate');
			$todate = $this->input->post('ToDate');
			list($fmmonth, $fmday, $fmyear) = explode("/", $fmdate);
			$fmdate =  $fmyear.'-'.$fmmonth.'-'.$fmday; 
			list($tomonth, $today, $toyear) = explode("/", $todate);
			$todate =  $toyear.'-'.$tomonth.'-'.$today; 
			$result=$this->kidsreward_model->Get(array(),$fmdate,$todate);
		}
		ELSEIF($this->input->post('sendmail_x'))
		{
			$todate = strtotime ( '+1 day' , strtotime (date('Y-m-d'))) ;
			$fmdate = strtotime ( '-8 day' , strtotime ( $todate ) ) ;
			$fmdate = date ('Y-m-d',$fmdate);
			$result=$this->kidsreward_model->Get(array('table'=>'kidsreward'),$fmdate,$todate);
			$CarlyAmt = 0;
			$CarlySignature = 0;
			$JessicaAmt = 0;
			$JessicaSignature = 0;
			$CarlyTotal = 0;
			$JessicaTotal = 0;
			$resultTotal=$this->general_model->_get(array('table'=>'kidsreward'));
			if($resultTotal){
				foreach($resultTotal as $row){
					if ($row->rewardid=='1') {
						$CarlyAmt += $row->amount;
						$CarlySignature += $row->signature;
						$CarlyTotal = $CarlyAmt + $CarlySignature * 2;
					}
					ELSE {
						$JessicaAmt += $row->amount;
						$JessicaSignature += $row->signature;
						$JessicaTotal = $JessicaAmt + $JessicaSignature * 2;
					}
				}
			}			
			$todaydate = date("l, F j, Y, g:i a");
			$email = "raymondlwhuang@yahoo.com";
			$to = "carly.huang@hotmail.com,jessicaanddarly@yahoo.ca";
			//$to = "raymondlwhuang@gmail.com,raymondlwhuang@yahoo.com";
			$cc = "";
			$subject = "Your reward";
			$headers = "From: raymondlwhuang@yahoo.com\r\n";
			$headers .= "Reply-To: raymondlwhuang@yahoo.com\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$message = '<html><body>';
			$message .= "<h1>Hello Carly and Jessica,</h1>";
			$message .= "<br/><br/>Here is your reward I got this week <br/>
			Hope you getting more reward continuously.<br/><br/><br/>";
			$message .= '
			<table style="border-style: solid;border-color:#0000ff;border-width: 3px;" width="100%">
			<tr>
				<th  style="border-style: solid;border-color:#0000ff;border-width: 3px;">
				<p>Reward To</p>
				</th>
				<th  style="border-style: solid;border-color:#0000ff;border-width: 3px;">
				<p>Date</p>
				</th>
				<th  style="border-style: solid;border-color:#0000ff;border-width: 3px;">
				<p>Amount</p>
				</th>
				<th  style="border-style: solid;border-color:#0000ff;border-width: 3px;">
				<p>Signature</p>
				</th>
				<th  style="border-style: solid;border-color:#0000ff;border-width: 3px;">
				<p>Description</p>
				</th>	
				</tr>';
				if($result){
				foreach($result as $nt){
				$message .= '
				<tr>
					<td  style="border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;">';
					if ($nt->rewardid == '1') {
					$message .= "Carly"; 
					}
					ELSE {
					$message .= "Jessica";	
					}
				$message .= "
				</td>
					<td  style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>";
					$message .= substr($nt->date,-5);
				$message .= "	
					</td>
					<td align='right'  style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
					$nt->amount
					</td>
					<td  style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
					$nt->signature
					</td>
					<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
					<font color = blue>".$nt->description."&nbsp;</font>
					</td>
				</tr>
				";
				}
				}
				$message .= "
				<tr>
				<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;' colspan='2'>
					Carly Total Now
				</td>	
				<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
					$CarlyAmt
				</td>	
				<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
					$CarlySignature
				</td>
				<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>\$$CarlyTotal
				</td>
				</tr>
				<tr>	
				<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;' colspan='2'>
					Jessica Total Now
				</td>	
				<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
					$JessicaAmt
				</td>	
				<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
					$JessicaSignature
				</td>	
				<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>\$$JessicaTotal
				</td>
				</tr>
				</table><br/><br/><br/>
				Daddy";		
				$message .= '</body></html>';
			if (preg_match("/bcc:/i", $email . " " . $message) == 0 &&          /* check for injected 'bcc' field */
				preg_match("/Content-Type:/i", $email . " " . $message) == 0 && /* check for injected 'content-type' field */
				preg_match("/cc:/i", $email . " " . $message) == 0 &&           /* check for injected 'cc' field */
				preg_match("/to:/i", $email . " " . $message) == 0) {           /* check for injected 'to' field */
				// Format the body of the email
				$message = "Email: $email\n" . $message . "\n\nSent from on: ($todaydate)\n";
				$sent = mail($to, $subject, $message, $headers) ;
				if($sent) {
					echo '<script type="text/javascript">document.getElementById("ErrorMessage").innerHTML = "Your mail was sent successfully.<br>Thanks for your comment"</script>';
				} else {
					echo '<script type="text/javascript">document.getElementById("ErrorMessage").innerHTML = "We encountered an error sending your mail"</script>';
				}
			} else  {
				echo '<script type="text/javascript">document.getElementById("ErrorMessage").innerHTML = "We encountered an error sending your mail"</script>';
			}
			//redirect('/KidsReward', 'refresh');
		}
		if(!isset($result) || (isset($result) && !$result)) {
			$todate = strtotime ( '+1 day' , strtotime (date('Y-m-d'))) ;
			$fmdate = strtotime ( '-8 day' , strtotime ( $todate ) ) ;
			$fmdate = date ('Y-m-d',$fmdate);
			$result=$this->kidsreward_model->Get(array('table'=>'kidsreward'),$fmdate,$todate);
		}
		$CarlyAmt = 0;
		$CarlySignature = 0;
		$JessicaAmt = 0;
		$JessicaSignature = 0;
		$CarlyTotal = 0;
		$JessicaTotal = 0;
		$resultTotal=$this->general_model->_get(array('table'=>'kidsreward'));
		if($resultTotal){
			foreach($resultTotal as $row){
				if ($row->rewardid=='1') {
					$CarlyAmt += $row->amount;
					$CarlySignature += $row->signature;
					$CarlyTotal = $CarlyAmt + $CarlySignature * 2;
				}
				ELSE {
					$JessicaAmt += $row->amount;
					$JessicaSignature += $row->signature;
					$JessicaTotal = $JessicaAmt + $JessicaSignature * 2;
				}
			}
		}	
		$data=array();
		if($result) $data['result']=$result;
		if(isset($rewardid)) $data['rewardid']=$rewardid;
		if(isset($date)) $data['date']=$date;
		if(isset($description)) $data['description']=$description;
		if(isset($searchID)) $data['searchID']=$searchID;
		if(isset($FmDate)) $data['FmDate']=$FmDate;
		if(isset($ToDate)) $data['ToDate']=$ToDate;
		if(isset($CarlyAmt)) $data['CarlyAmt']=$CarlyAmt;
		if(isset($CarlySignature)) $data['CarlySignature']=$CarlySignature;
		if(isset($CarlyTotal)) $data['CarlyTotal']=$CarlyTotal;
		if(isset($JessicaAmt)) $data['JessicaAmt']=$JessicaAmt;
		if(isset($JessicaSignature)) $data['JessicaSignature']=$JessicaSignature;
		if(isset($JessicaTotal)) $data['JessicaTotal']=$JessicaTotal;
		$this->load->view("kidsReward_view",$data);
	}
}
